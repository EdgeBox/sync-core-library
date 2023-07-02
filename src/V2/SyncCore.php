<?php

namespace EdgeBox\SyncCore\V2;

use EdgeBox\SyncCore\Exception\BadRequestException;
use EdgeBox\SyncCore\Exception\ForbiddenException;
use EdgeBox\SyncCore\Exception\InternalContentSyncError;
use EdgeBox\SyncCore\Exception\NotFoundException;
use EdgeBox\SyncCore\Exception\SyncCoreException;
use EdgeBox\SyncCore\Exception\TimeoutException;
use EdgeBox\SyncCore\Exception\UnauthorizedException;
use EdgeBox\SyncCore\Interfaces\IApplicationInterface;
use EdgeBox\SyncCore\Interfaces\ISyncCore;
use EdgeBox\SyncCore\V2\Configuration\ConfigurationService;
use EdgeBox\SyncCore\V2\Embed\EmbedService;
use EdgeBox\SyncCore\V2\Raw\Api\DefaultApi;
use EdgeBox\SyncCore\V2\Raw\Configuration;
use EdgeBox\SyncCore\V2\Raw\Model\AuthenticationType;
use EdgeBox\SyncCore\V2\Raw\Model\CreateAuthenticationDto;
use EdgeBox\SyncCore\V2\Raw\Model\CreateFileDto;
use EdgeBox\SyncCore\V2\Raw\Model\CreateSiteDto;
use EdgeBox\SyncCore\V2\Raw\Model\EntityTypeVersionUsage;
use EdgeBox\SyncCore\V2\Raw\Model\FeatureFlagSummary;
use EdgeBox\SyncCore\V2\Raw\Model\FileEntity;
use EdgeBox\SyncCore\V2\Raw\Model\FileStatus;
use EdgeBox\SyncCore\V2\Raw\Model\FileType;
use EdgeBox\SyncCore\V2\Raw\Model\PagedRequestList;
use EdgeBox\SyncCore\V2\Raw\Model\RegisterNewSiteDto;
use EdgeBox\SyncCore\V2\Raw\Model\RegisterSiteDto;
use EdgeBox\SyncCore\V2\Raw\Model\RequestResponseDto;
use EdgeBox\SyncCore\V2\Raw\Model\RequestResponseDtoResponse;
use EdgeBox\SyncCore\V2\Raw\Model\SiteEntity;
use EdgeBox\SyncCore\V2\Raw\Model\SiteRestUrls;
use EdgeBox\SyncCore\V2\Raw\Model\SuccessResponse;
use EdgeBox\SyncCore\V2\Raw\ObjectSerializer;
use EdgeBox\SyncCore\V2\Syndication\SyndicationService;
use Exception;
use Firebase\JWT\JWT;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ConnectException;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Psr7\MultipartStream;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\RequestOptions;

class SyncCore implements ISyncCore
{
    // Keep tokens alive for 4 hours.
    public const JWT_LIFETIME = 60 * 60 * 4;
    protected const PLACEHOLDER_SITE_BASE_URL = '[site.baseUrl]';
    protected const PLACEHOLDER_FLOW_MACHINE_NAME = '[flow.machineName]';
    protected const PLACEHOLDER_ENTITY_SHARED_ID = '[entity.sharedId]';
    /**
     * @var string
     *             The base URL of the remote Sync Core. See Pool::$backend_url
     */
    protected $base_url;

    /**
     * @var DefaultApi
     */
    protected $client;

    /**
     * @var IApplicationInterface
     */
    protected $application;

    /**
     * @var string
     */
    protected $cloud_base_url;

    /**
     * @var string
     */
    protected $cloud_embed_url;

    /**
     * @var int
     */
    protected $default_timeout = 15;

    /**
     * @var int
     */
    protected $timeout_quick = 7;

    /**
     * @param string $base_url
     *                         The Sync Core base URL
     */
    public function __construct(IApplicationInterface $application, string $base_url)
    {
        if ('/sync-core' != substr($base_url, -10)) {
            throw new InternalContentSyncError("Invalid base URL doesn't end with /sync-core");
        }

        if (getenv('SYNC_CORE_DEFAULT_TIMEOUT')) {
            $this->default_timeout = (int) getenv('SYNC_CORE_DEFAULT_TIMEOUT');
        }

        $this->application = $application;
        // As the /sync-core will be exported with the routes in the swagger.yaml docs
        // that is fed into the openapi generator, our library will already include
        // that prefix for all routes. So we need to cut it off or we would end up
        // with an incorrect double-prefix of /sync-core/sync-core.
        $this->base_url = substr($base_url, 0, -10);
        $this->cloud_base_url = getenv('CONTENT_SYNC_CLOUD_BASE_URL')
        ? getenv('CONTENT_SYNC_CLOUD_BASE_URL')
        : 'https://app.cms-content-sync.io';
        $this->cloud_embed_url = getenv('CONTENT_SYNC_CLOUD_EMBED_URL')
        ? getenv('CONTENT_SYNC_CLOUD_EMBED_URL')
        : 'https://embed.cms-content-sync.io';

        $configuration = new Configuration();
        $configuration->setHost($this->base_url);

        $this->client = new DefaultApi(
            $application->getHttpClient(),
            $configuration
        );
    }

    public function getBaseUrl()
    {
        return $this->base_url.'/sync-core';
    }

    public function getClient()
    {
        return $this->client;
    }

    /**
     * @param string                $base_url
     * @param IApplicationInterface $application
     *
     * @return SyncCore
     */
    public static function get($base_url, $application)
    {
        static $instances = [];

        if (isset($instances[$base_url])) {
            return $instances[$base_url];
        }

        return $instances[$base_url] = new SyncCore($application, $base_url);
    }

    public function getPublicClient()
    {
        static $client = null;
        if (!$client) {
            $client = new Client();
        }

        return $client;
    }

    /**
     * @param string $type
     * @param string $file_name
     * @param string $content
     * @param bool   $avoid_duplicates if set, the file hash will be sentand if an identical file already exists, it will not be uploaded again
     * @param bool   $is_configuration what permissions to set
     *
     * @throws BadRequestException
     * @throws ForbiddenException
     * @throws NotFoundException
     * @throws SyncCoreException
     * @throws TimeoutException
     *
     * @return FileEntity
     */
    public function sendFile($type, $file_name, $content, $avoid_duplicates = true, $is_configuration = false)
    {
        $fileDto = new CreateFileDto();

        /**
         * @var float $file_size
         */
        $file_size = strlen($content);

        /**
         * @var FileType $type
         */
        $fileDto->setType($type);
        $fileDto->setFileName($file_name);
        $fileDto->setFileSize($file_size);

        if ($avoid_duplicates) {
            $hash = hash('sha1', $content);
            $fileDto->setHash($hash);
        }

        $permissions = $is_configuration
            ? IApplicationInterface::SYNC_CORE_PERMISSIONS_CONFIGURATION
            : IApplicationInterface::SYNC_CORE_PERMISSIONS_CONTENT;

        $request = $this->getClient()->fileControllerCreateRequest(createFileDto: $fileDto);
        /**
         * @var FileEntity $file
         */
        $file = $this->sendToSyncCoreAndExpect($request, FileEntity::class, $permissions);

        $upload_url = $file->getUploadUrl();
        /**
         * @var string $status
         */
        $status = $file->getStatus();
        if (!$upload_url || FileStatus::_400_READY === $status) {
            if (!$avoid_duplicates) {
                throw new InternalContentSyncError('File has no upload URL.');
            }

            // File already exists and can be re-used immediately.
            return $file;
        }

        $max_size = $file->getMaxFileSize();
        if ($max_size && $file_size > $max_size) {
            $file_size_human_friendly = Helper::formatStorageSize($file_size, true);
            $max_size_human_friendly = Helper::formatStorageSize($max_size);
            if (FileType::ENTITY_PREVIEW == $file->getType()) {
                throw new BadRequestException("Preview of {$file_size_human_friendly} exceeds max size of {$max_size_human_friendly}.");
            }
            if (FileType::REMOTE_FLOW_CONFIG == $file->getType()) {
                throw new BadRequestException("Flow config of {$file_size_human_friendly} exceeds max size of {$max_size_human_friendly}.");
            }

            throw new BadRequestException("File {$file_name} with {$file_size_human_friendly} exceeds upload limit of {$max_size_human_friendly}.");
        }

        // Raw body upload
        if (preg_match('@https://[^/]*amazonaws.com/@', $upload_url)) {
            $response = $this->getPublicClient()->request('PUT', $upload_url, [
                RequestOptions::TIMEOUT => $this->default_timeout,
                RequestOptions::BODY => $content,
                RequestOptions::HEADERS => [
                    'Content-Type' => 'application/octet-stream',
                    'Accept' => '*/*',
                ],
            ]);
        }
        // Multipart upload
        else {
            $httpBody = new MultipartStream([
                [
                    'name' => 'file',
                    'contents' => $content,
                    // The Sync Core won't accept requests that don't contain a filename.
                    'filename' => $file_name,
                ],
            ]);
            $request = new Request(
                'PUT',
                $upload_url,
                [],
                $httpBody
            );
            $this->sendRaw($request, []);
        }

        $request = $this->getClient()->fileControllerFileUploadedRequest(id: $file->getId());

        return $this->sendToSyncCoreAndExpect($request, FileEntity::class, $permissions);
    }

    /**
     * Send the given request to the server. As the OpenAPI generator will add all kind
     * of unnecessary nonsense, we only want the raw request and then handle status codes
     * etc ourselves.
     *
     * @param array $options
     *
     * @throws BadRequestException
     * @throws ForbiddenException
     * @throws NotFoundException
     * @throws SyncCoreException
     * @throws TimeoutException
     *
     * @return mixed
     */
    public function sendRaw(Request $request, $options = [])
    {
        try {
            $options = [
                RequestOptions::HTTP_ERRORS => false,
            ] + $this->application->getHttpOptions() + $options;

            if (empty($options[RequestOptions::TIMEOUT])) {
                $options[RequestOptions::TIMEOUT] = $this->default_timeout;
            }

            //\Drupal::messenger()->addMessage($request->getMethod().' '.print_r($request->getBody()->__toString(),1));

            $response = $this->application->getHttpClient()->send($request, $options);
        } catch (ConnectException $e) {
            throw new TimeoutException('The Sync Core did not respond in time for '.$request->getMethod().' '.Helper::obfuscateCredentials($request->getUri()).' '.$e->getMessage());
        } catch (GuzzleException $e) {
            throw new SyncCoreException($e->getMessage());
        } catch (Exception $e) {
            throw new SyncCoreException($e->getMessage());
        }

        $status = $response->getStatusCode();

        $response_body = $response->getBody();

        if (200 !== $status && 201 !== $status) {
            $data = json_decode($response_body, true);
            $message = isset($data['message']) ? $data['message'] : $response_body.'';
            if (!is_string($message)) {
                $message = json_encode($message);
            }
            if (400 === $status) {
                throw new BadRequestException('The Sync Core responded with 400 Bad Request for '.$request->getMethod().' '.Helper::obfuscateCredentials($request->getUri()).' '.$message, $status, $response->getReasonPhrase(), $response_body);
            }
            if (401 === $status) {
                throw new UnauthorizedException('The Sync Core responded with 401 Unauthorized for '.$request->getMethod().' '.Helper::obfuscateCredentials($request->getUri()).' '.$message, $status, $response->getReasonPhrase(), $response_body);
            }
            if (403 === $status) {
                throw new ForbiddenException('The Sync Core responded with 403 Forbidden for '.$request->getMethod().' '.Helper::obfuscateCredentials($request->getUri()).' '.$message, $status, $response->getReasonPhrase(), $response_body);
            }
            if (404 === $status) {
                throw new NotFoundException('The Sync Core responded with 404 Not Found for '.$request->getMethod().' '.Helper::obfuscateCredentials($request->getUri()).' '.$message, $status, $response->getReasonPhrase(), $response_body);
            }

            throw new SyncCoreException('The Sync Core responded with a non-OK status code for '.$request->getMethod().' '.Helper::obfuscateCredentials($request->getUri()).' '.$message, $status, $response->getReasonPhrase(), $response_body);
        }

        return (string) $response_body;
    }

    /**
     * Send the given request to the server. As the OpenAPI generator will add all kind
     * of unnecessary nonsense, we only want the raw request and then handle status codes
     * etc ourselves.
     *
     * @throws BadRequestException
     * @throws ForbiddenException
     * @throws NotFoundException
     * @throws SyncCoreException
     * @throws TimeoutException
     *
     * @return mixed
     */
    public function sendToSyncCore(Request $request, string $permissions, bool $quick = false)
    {
        $jwt = $this->createJwt($permissions);

        return $this->sendToSyncCoreWithJwt($request, $jwt, $quick);
    }

    public function sendToSyncCoreWithJwt(Request $request, string $jwt, bool $quick = false)
    {
        $options = [];
        $options[RequestOptions::HEADERS]['Authorization'] = 'Bearer '.$jwt;

        if ($quick) {
            $options[RequestOptions::TIMEOUT] = $this->timeout_quick;
        }

        return $this->sendRaw($request, $options);
    }

    public function sendToSyncCoreWithJwtAndExpect(Request $request, string $class, string $jwt)
    {
        $response = $this->sendToSyncCoreWithJwt($request, $jwt);

        return @ObjectSerializer::deserialize($response, $class, []);
    }

    /**
     * @throws BadRequestException
     * @throws ForbiddenException
     * @throws NotFoundException
     * @throws SyncCoreException
     * @throws TimeoutException
     *
     * @return object|Raw\Model\ModelInterface
     */
    public function sendToSyncCoreAndExpect(Request $request, string $class, string $permissions, bool $quick = false)
    {
        $response = $this->sendToSyncCore($request, $permissions, $quick);

        return @ObjectSerializer::deserialize($response, $class, []);
    }

    public function isSiteRegistered()
    {
        return $this->hasValidV2SiteId();
    }

    public function createJwt($permissions)
    {
        $uuid = $this->application->getSiteUuid();
        if (!$uuid) {
            throw new InternalContentSyncError("This site is not registered yet; can't execute a signed request.");
        }

        $secret = $this->getSiteSecret();
        $payload = [
            'type' => 'site',
            'scopes' => IApplicationInterface::SYNC_CORE_PERMISSIONS_CONFIGURATION === $permissions
                ? [
                    IApplicationInterface::SYNC_CORE_PERMISSIONS_CONFIGURATION,
                    IApplicationInterface::SYNC_CORE_PERMISSIONS_CONTENT,
                ]
                : [$permissions],
            'provider' => 'jwt-header',
            'uuid' => $uuid,
            'exp' => time() + self::JWT_LIFETIME,
        ];

        return JWT::encode($payload, $secret, 'HS256');
    }

    public function getSyncCoreDomain()
    {
        $url = parse_url($this->base_url);

        return $url['host'];
    }

    public function registerNewSiteWithToken(array $options, string $token)
    {
        $dto = new RegisterNewSiteDto($options);
        // TODO: Drupal/Interface: When the password changes, we need to make a request to the Sync Core using
        //   the old password to set the new password. If the request fails, the password
        //   can't be changed.
        $dto->setSecret($this->getSiteSecret());

        $dto->setToken($token);

        $this->addSiteDetails($dto);

        $invalid = $dto->listInvalidProperties();

        if (count($invalid)) {
            throw new InternalContentSyncError('Invalid options: '.print_r($invalid, true));
        }

        $request = $this->client->siteControllerRegisterNewRequest(registerNewSiteDto: $dto);
        $entity = $this->sendToSyncCoreWithJwtAndExpect($request, SiteEntity::class, $token);

        $siteId = $entity->getUuid();
        $this->application->setSiteUuid($siteId);
        // Save the credentials to the Sync Core so it can connect to the site as well.
        $auth = $this->application->getAuthentication();

        $authentication = new CreateAuthenticationDto();
        /**
         * @var AuthenticationType $type
         */
        $type = IApplicationInterface::AUTHENTICATION_TYPE_COOKIE === $auth['type']
        ? AuthenticationType::DRUPAL8_SERVICES
            : AuthenticationType::BASIC_AUTH;
        $authentication->setType($type);
        $authentication->setUsername($auth['username']);
        $authentication->setPassword($auth['password']);

        $request = $this->client->authenticationControllerCreateRequest(createAuthenticationDto: $authentication);
        $this->sendToSyncCore($request, IApplicationInterface::SYNC_CORE_PERMISSIONS_CONFIGURATION);
    }

    public function registerSiteWithJwt($options)
    {
        $dto = new RegisterSiteDto($options);
        // TODO: Drupal/Interface: When the password changes, we need to make a request to the Sync Core using
        //   the old password to set the new password. If the request fails, the password
        //   can't be changed.
        $dto->setBaseUrl($this->application->getSiteBaseUrl());
        $dto->setSecret($this->getSiteSecret());

        $urls = new SiteRestUrls();

        $urls->setCreateEntity(self::PLACEHOLDER_SITE_BASE_URL.$this->getRelativeReference(IApplicationInterface::REST_ACTION_CREATE_ENTITY));
        $urls->setDeleteEntity(self::PLACEHOLDER_SITE_BASE_URL.$this->getRelativeReference(IApplicationInterface::REST_ACTION_DELETE_ENTITY));
        $urls->setRetrieveEntity(self::PLACEHOLDER_SITE_BASE_URL.$this->getRelativeReference(IApplicationInterface::REST_ACTION_RETRIEVE_ENTITY));
        $urls->setListEntities(self::PLACEHOLDER_SITE_BASE_URL.$this->getRelativeReference(IApplicationInterface::REST_ACTION_LIST_ENTITIES));
        $urls->setSiteStatus(self::PLACEHOLDER_SITE_BASE_URL.$this->getRelativeReference(IApplicationInterface::REST_ACTION_SITE_STATUS));

        $dto->setRestUrls($urls);

        $invalid = $dto->listInvalidProperties();

        if (count($invalid)) {
            throw new InternalContentSyncError('Invalid options: '.print_r($invalid, true));
        }

        $request = $this->client->siteControllerRegisterRequest(registerSiteDto: $dto);
        $entity = $this->sendToSyncCoreWithJwtAndExpect($request, SiteEntity::class, $options['jwt']);

        $siteId = $entity->getUuid();
        $this->application->setSiteUuid($siteId);
        // Save the credentials to the Sync Core so it can connect to the site as well.
        $auth = $this->application->getAuthentication();

        $authentication = new CreateAuthenticationDto();
        /**
         * @var AuthenticationType $type
         */
        $type = IApplicationInterface::AUTHENTICATION_TYPE_COOKIE === $auth['type']
        ? AuthenticationType::DRUPAL8_SERVICES
        : AuthenticationType::BASIC_AUTH;
        $authentication->setType($type);
        $authentication->setUsername($auth['username']);
        $authentication->setPassword($auth['password']);

        $request = $this->client->authenticationControllerCreateRequest(createAuthenticationDto: $authentication);
        $this->sendToSyncCore($request, IApplicationInterface::SYNC_CORE_PERMISSIONS_CONFIGURATION);
    }

    public function getCloudEmbedUrl()
    {
        return $this->cloud_embed_url;
    }

    public function batch()
    {
        return new Batch($this);
    }

    public function featureEnabled(string $name, bool $quick = false)
    {
        $features = $this->getFeatures($quick);

        return !empty($features[$name]) && (bool) $features[$name];
    }

    public function getFeatures(bool $quick = false)
    {
        static $features = null;
        if (null !== $features) {
            return $features;
        }

        $request = $this->client->featuresControllerSummaryRequest();
        /**
         * @var FeatureFlagSummary $response
         */
        $response = $this->sendToSyncCoreAndExpect($request, FeatureFlagSummary::class, IApplicationInterface::SYNC_CORE_PERMISSIONS_CONFIGURATION, $quick);

        $flags = (array) $response->getFlags();

        $features = [
            ISyncCore::FEATURE_REFRESH_AUTHENTICATION => 0,
            ISyncCore::FEATURE_INDEPENDENT_FLOW_CONFIG => 1,
            ISyncCore::FEATURE_PULL_ALL_WITHOUT_POOL => 1,
            ISyncCore::FEATURE_PUSH_TO_MULTIPLE_POOLS => 1,
        ] + $flags;

        return $features;
    }

    public function getApplication()
    {
        return $this->application;
    }

    public function getReservedPropertyNames()
    {
        // All handled independently now, so we don't have any reserved names.
        return [];
    }

    public function getInternalSiteId($uuid)
    {
        return $this->loadSiteByIdOrUuid($uuid)->getId();
    }

    public function getExternalSiteId($id)
    {
        return $this->loadSiteByIdOrUuid($id)->getUuid();
    }

    public function getSiteName($uuid = null)
    {
        if (!$uuid) {
            if (!$this->hasValidV2SiteId()) {
                throw new InternalContentSyncError("Site is not registered yet. Can't provide a name.");
            }

            $uuid = $this->application->getSiteUuid();
        }

        return $this->loadSiteByIdOrUuid($uuid)->getName();
    }

    public function getSitesWithDifferentEntityTypeVersion(string $pool_id, string $entity_type, string $bundle, string $target_version)
    {
        $request = $this->client->remoteEntityTypeVersionControllerGetVersionUsageRequest(
            versionId: $target_version,
            machineName: $bundle,
            namespaceMachineName: $entity_type
        );

        /**
         * @var EntityTypeVersionUsage $response
         */
        $response = $this->sendToSyncCoreAndExpect(
            $request,
            EntityTypeVersionUsage::class,
            IApplicationInterface::SYNC_CORE_PERMISSIONS_CONFIGURATION
        );

        // FIXME: With the new Sync Core version we have a lot more helpful data available.
        //  Once SC 1 is dead, we can improve and provide that here, too.
        $result = [];

        foreach ($response->getDifferent() as $different) {
            $local_missing = [];
            foreach ($different->getAdditionalProperties() as $property) {
                $local_missing[] = $property->getMachineName();
            }
            if (count($local_missing)) {
                foreach ($different->getSites() as $site) {
                    $result[$site->getUuid()]['local_missing'] = $local_missing;
                }
            }

            $remote_missing = [];
            foreach ($different->getMissingProperties() as $property) {
                $remote_missing[] = $property->getMachineName();
            }
            if (count($remote_missing)) {
                foreach ($different->getSites() as $site) {
                    $result[$site->getUuid()]['remote_missing'] = $remote_missing;
                }
            }
        }

        return $result;
    }

    public function getConfigurationService()
    {
        static $cache = null;
        if ($cache) {
            return $cache;
        }

        return $cache = new ConfigurationService($this);
    }

    public function getReportingService()
    {
        static $cache = null;
        if ($cache) {
            return $cache;
        }

        return $cache = new ReportingService($this);
    }

    /**
     * @return SyndicationService
     */
    public function getSyndicationService()
    {
        static $cache = null;
        if ($cache) {
            return $cache;
        }

        return $cache = new SyndicationService($this);
    }

    public function getEmbedService()
    {
        static $cache = null;
        if ($cache) {
            return $cache;
        }

        return $cache = new EmbedService($this);
    }

    public function isDirectUserAccessEnabled($set = null)
    {
        // Sync Core 2.0 has it always enabled.
        return true;
    }

    public function registerSite($force = false)
    {
        // TODO: Allow if a special multi-site-register JWT is provided.
        return '';
    }

    public function setDomains(array $domains)
    {
        if (!$this->featureEnabled('domains')) {
            return;
        }

        if (!$this->isSiteRegistered()) {
            return;
        }

        $dto = $this->getSiteUpdateDto();

        if (!count($domains)) {
            $dto->setDomains(null);
        } else {
            $dto->setDomains($domains);
        }
        $request = $this->client->siteControllerUpdateRequest(createSiteDto: $dto);
        $this->sendToSyncCore($request, IApplicationInterface::SYNC_CORE_PERMISSIONS_CONFIGURATION);
    }

    public function setSiteName(string $set)
    {
        $dto = $this->getSiteUpdateDto();
        if ($dto->getName() === $set) {
            return;
        }

        $dto->setName($set);
        $request = $this->client->siteControllerUpdateRequest(createSiteDto: $dto);
        $this->sendToSyncCore($request, IApplicationInterface::SYNC_CORE_PERMISSIONS_CONFIGURATION);
    }

    public function updateSiteAtSyncCore()
    {
        if (!$this->isSiteRegistered()) {
            return;
        }

        $dto = $this->getSiteUpdateDto();

        $this->addSiteDetails($dto);

        $request = $this->client->siteControllerUpdateRequest($dto);
        $this->sendToSyncCore($request, IApplicationInterface::SYNC_CORE_PERMISSIONS_CONFIGURATION);
    }

    public function verifySiteId()
    {
        // As sites are registered globally now, we don't need additional verification.
        // Sites can't be registered multiple times with the same ID or base URL.
        return null;
    }

    /**
     * {@inheritDoc}
     */
    public function pollRequests($limit = 1)
    {
        $request = $this->client->siteControllerGetRequestsRequest($limit);
        /**
         * @var PagedRequestList $response
         */
        $response = $this->sendToSyncCoreAndExpect($request, PagedRequestList::class, IApplicationInterface::SYNC_CORE_PERMISSIONS_CONFIGURATION);

        return $response->getItems();
    }

    /**
     * {@inheritDoc}
     */
    public function respondToRequest(string $id, int $statusCode, string $statusText, array $headers, string $body)
    {
        $wrapper = new RequestResponseDto();
        $dto = new RequestResponseDtoResponse();
        $dto->setResponseStatusCode($statusCode);
        $dto->setResponseStatusText($statusText);
        $dto->setResponseHeaders($headers);
        $dto->setResponseBody($body);
        $wrapper->setResponse($dto);
        $request = $this->client->siteControllerRespondToRequestRequest($id, $wrapper);
        /**
         * @var SuccessResponse $response
         */
        $response = $this->sendToSyncCoreAndExpect($request, SuccessResponse::class, IApplicationInterface::SYNC_CORE_PERMISSIONS_CONFIGURATION);

        return $response->getSuccess();
    }

    /**
     * Load a site by either it's external or internal ID.
     *
     * @return SiteEntity
     */
    protected function loadSiteByIdOrUuid(string $uuid)
    {
        // Site IDs from Sync Core V1 are not a UUID, so we check whether the given site ID
        // is a UUID and if it's not, the site must be re-registered first.
        if (1 === preg_match('/^[0-9A-F]{8}-[0-9A-F]{4}-4[0-9A-F]{3}-[89AB][0-9A-F]{3}-[0-9A-F]{12}$/i', $uuid)) {
            $request = $this->client->siteControllerItemByUuidRequest($uuid);
        } else {
            $request = $this->client->siteControllerItemRequest($uuid);
        }

        return $this->sendToSyncCoreAndExpect($request, SiteEntity::class, IApplicationInterface::SYNC_CORE_PERMISSIONS_CONTENT);
    }

    /**
     * @param CreateSiteDto|RegisterNewSiteDto $dto
     */
    protected function addSiteDetails($dto)
    {
        $dto->setName($this->application->getSiteName());
        $dto->setBaseUrl($this->application->getSiteBaseUrl());

        /**
         * @var \EdgeBox\SyncCore\V2\Raw\Model\SiteApplicationType $app_type
         */
        $app_type = $this->application->getApplicationId();
        $dto->setAppType($app_type);
        $dto->setAppVersion($this->application->getApplicationVersion());
        $dto->setAppModuleVersion($this->application->getApplicationModuleVersion());

        $urls = new SiteRestUrls();

        $urls->setCreateEntity(self::PLACEHOLDER_SITE_BASE_URL.$this->getRelativeReference(IApplicationInterface::REST_ACTION_CREATE_ENTITY));
        $urls->setDeleteEntity(self::PLACEHOLDER_SITE_BASE_URL.$this->getRelativeReference(IApplicationInterface::REST_ACTION_DELETE_ENTITY));
        $urls->setRetrieveEntity(self::PLACEHOLDER_SITE_BASE_URL.$this->getRelativeReference(IApplicationInterface::REST_ACTION_RETRIEVE_ENTITY));
        $urls->setListEntities(self::PLACEHOLDER_SITE_BASE_URL.$this->getRelativeReference(IApplicationInterface::REST_ACTION_LIST_ENTITIES));
        $urls->setSiteStatus(self::PLACEHOLDER_SITE_BASE_URL.$this->getRelativeReference(IApplicationInterface::REST_ACTION_SITE_STATUS));

        $dto->setRestUrls($urls);
    }

    protected function getSiteUpdateDto()
    {
        if (!$this->hasValidV2SiteId()) {
            throw new InternalContentSyncError("Site is not registered yet. Can't change site name.");
        }

        $id = $this->application->getSiteUuid();
        $request = $this->client->siteControllerItemByUuidRequest(uuid: $id);
        $current = $this->sendToSyncCoreAndExpect($request, SiteEntity::class, IApplicationInterface::SYNC_CORE_PERMISSIONS_CONFIGURATION);

        $serialized = json_decode(json_encode($current->jsonSerialize()), true);

        return new CreateSiteDto($serialized);
    }

    protected function hasValidV2SiteId()
    {
        $site_id = $this->application->getSiteUuid();
        if (!$site_id) {
            return false;
        }

        // Site IDs from Sync Core V1 are not a UUID, so we check whether the given site ID
        // is a UUID and if it's not, the site must be re-registered first.
        return 1 === preg_match('/^[0-9A-F]{8}-[0-9A-F]{4}-4[0-9A-F]{3}-[89AB][0-9A-F]{3}-[0-9A-F]{12}$/i', $site_id);
    }

    protected function getSiteSecret()
    {
        return $this->application->getAuthentication()['password'];
    }

    protected function getRelativeReference(string $action)
    {
        if (IApplicationInterface::REST_ACTION_SITE_STATUS === $action) {
            $relative = $this->application->getRelativeReferenceForSiteRestCall($action);
        } else {
            $relative = $this->application->getRelativeReferenceForRestCall(
                self::PLACEHOLDER_FLOW_MACHINE_NAME,
                $action
            );
        }

        if ('/' !== $relative[0]) {
            throw new InternalContentSyncError('Relative reference must start with a slash /.');
        }

        return $relative;
    }
}
