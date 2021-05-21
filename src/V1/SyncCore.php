<?php

namespace EdgeBox\SyncCore\V1;

use EdgeBox\SyncCore\Exception\NotFoundException;
use EdgeBox\SyncCore\Exception\SiteVerificationFailedException;
use EdgeBox\SyncCore\Interfaces\ISyncCore;
use EdgeBox\SyncCore\V1\Configuration\ConfigurationService;
use EdgeBox\SyncCore\V1\Query\Condition\DataCondition;
use EdgeBox\SyncCore\V1\Query\Condition\ParentCondition;
use EdgeBox\SyncCore\V1\Query\PingQuery;
use EdgeBox\SyncCore\V1\Storage\ApiStorage;
use EdgeBox\SyncCore\V1\Storage\ConnectionStorage;
use EdgeBox\SyncCore\V1\Storage\InstanceStorage;
use EdgeBox\SyncCore\V1\Storage\PreviewEntityStorage;
use EdgeBox\SyncCore\V1\Syndication\SyndicationService;

/**
 * Class Client.
 *
 * The client used by the Storage to connect to the Sync Core. You can imagine
 * this to be a remote database connection where the Storage talks to individual
 * tables / collections.
 */
class SyncCore implements ISyncCore
{
    /**
     * @var \EdgeBox\SyncCore\V1\Storage
     */
    public $storage;

    /**
     * @var string
     *             The base URL of the remote Sync Core. See Pool::$backend_url
     */
    protected $base_url;

    /**
     * @var \EdgeBox\SyncCore\V1\SyncCoreClient
     */
    protected $client;

    /**
     * @var \EdgeBox\SyncCore\Interfaces\IApplicationInterface
     */
    protected $application;

    /**
     * @param \EdgeBox\SyncCore\Interfaces\IApplicationInterface $application
     * @param string                                             $base_url
     *                                                                        The Sync Core base URL
     */
    public function __construct($application, $base_url)
    {
        $this->application = $application;
        $this->base_url = $base_url;

        $this->client = new SyncCoreClient($this);

        $this->storage = new Storage($this);
    }

    /**
     * @param string                                             $base_url
     * @param \EdgeBox\SyncCore\Interfaces\IApplicationInterface $application
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

    /**
     * {@inheritdoc}
     */
    public function getReportingService()
    {
        static $cache = null;
        if ($cache) {
            return $cache;
        }

        return $cache = new ReportingService($this);
    }

    /**
     * {@inheritdoc}
     */
    public function getSyndicationService()
    {
        static $cache = null;
        if ($cache) {
            return $cache;
        }

        return $cache = new SyndicationService($this);
    }

    /**
     * {@inheritdoc}
     */
    public function getConfigurationService()
    {
        static $cache = null;
        if ($cache) {
            return $cache;
        }

        return $cache = new ConfigurationService($this);
    }

    public function getEmbedService()
    {
        return null;
    }

    /**
     * {@inheritdoc}
     */
    public function batch()
    {
        return new Batch($this);
    }

    /**
     * {@inheritdoc}
     */
    public function canHandleFlowConfigurationIndependently()
    {
        return false;
    }

    /**
     * @return \EdgeBox\SyncCore\Interfaces\IApplicationInterface
     */
    public function getApplication()
    {
        return $this->application;
    }

    /**
     * @return string
     */
    public function getBaseUrl($strip_credentials = false)
    {
        if ($strip_credentials) {
            $parts = parse_url($this->base_url);
            unset($parts['user']);
            unset($parts['pass']);

            $port = isset($parts['port']) ? ':'.$parts['port'] : '';

            return "{$parts['scheme']}://{$parts['host']}{$port}{$parts['path']}";
        }

        return $this->base_url;
    }

    /**
     * @return \EdgeBox\SyncCore\V1\SyncCoreClient
     */
    public function getClient()
    {
        return $this->client;
    }

    /**
     * @param string $site_url
     * @param string $method
     * @param array  $authentication
     *
     * @return bool
     *
     * @throws \EdgeBox\SyncCore\Exception\SyncCoreException
     */
    public function requestPing($site_url, $method, $authentication)
    {
        return PingQuery
      ::create($this, null)
      ->setSiteUrl($site_url)
      ->setMethod($method)
      ->setAuthentication($authentication)
      ->execute()
      ->succeeded();
    }

    /**
     * {@inheritdoc}
     */
    public function isDirectUserAccessEnabled($set = null)
    {
        if (null !== $set) {
            /**
             * @var \EdgeBox\SyncCore\V1\Storage\ConnectionStorage $storage
             */
            $storage = $this
        ->storage->getConnectionStorage();

            /**
             * @var \EdgeBox\SyncCore\V1\Entity\EntityPreviewConnection $connection
             */
            $connection = $storage
        ->getEntity(PreviewEntityStorage::ID);

            $connection
        ->allowPublicAccess($set)
        ->execute();

            return null;
        }

        try {
            $connection = $this
        ->storage->getConnectionStorage()
        ->getItem(PreviewEntityStorage::ID)
        ->execute()
        ->getItem();
        } catch (NotFoundException $e) {
            return null;
        }

        if (isset($connection['options'][PreviewEntityStorage::PUBLIC_ACCESS_OPTION_NAME])) {
            if ($connection['options'][PreviewEntityStorage::PUBLIC_ACCESS_OPTION_NAME]) {
                return true;
            } else {
                return false;
            }
        }

        return null;
    }

    /**
     * {@inheritdoc}
     */
    public function getSiteName($id = null)
    {
        try {
            $site = $this
        ->storage->getInstanceStorage()
        ->getItem($id ? $id : $this->application->getSiteId())
        ->execute()
        ->getItem();
        } catch (NotFoundException $e) {
            return null;
        }

        return $site['name'];
    }

    /**
     * @return bool
     */
    public function setSiteName(string $set)
    {
        $site = $this
      ->storage->getInstanceStorage()
      ->getItem($this->application->getSiteId())
      ->execute()
      ->getItem();

        $site['name'] = $set;

        return $this
      ->storage->getInstanceStorage()
      ->updateItem($this->application->getSiteId(), $site)
      ->execute()
      ->succeeded();
    }

    public function isSiteRegistered()
    {
        if (!$this->application->getSiteId()) {
            return false;
        }

        try {
            $site = $this
          ->storage->getInstanceStorage()
          ->getItem($this->application->getSiteId())
          ->execute()
          ->getItem();

            // No match: Warn user and don't export configuration.
            if ($site['base_url'] !== $this->application->getSiteBaseUrl()) {
                return false;
            }

            return true;
        }
        // Ignore "not found" as we're just about to export the configuration for
        // the first time then.
        catch (NotFoundException $e) {
            return false;
        }
    }

    /**
     * {@inheritdoc}
     */
    public function registerSite($force = false)
    {
        if (!$force && $this->application->getSiteId()) {
            $sites = $this->verifySiteId();
            if ($sites) {
                throw new SiteVerificationFailedException('A site with ID '.array_keys($sites)[0].' and base URL '.array_values($sites)[0].' already exists.');
            }
        }

        $machine_name = $this->application->getSiteMachineName();

        if (!$machine_name) {
            $n = 1;
            while (true) {
                $machine_name = 's'.$n;
                try {
                    $this
            ->storage->getInstanceStorage()
            ->getItem($machine_name)
            ->execute();
                } // Unused ID- keep it.
                catch (NotFoundException $e) {
                    break;
                }
                ++$n;
            }
            $this->application->setSiteMachineName($machine_name);
        }

        $this
      ->storage->getInstanceStorage()
      ->createItem([
          // Old Sync Core: Use machine name as site ID.
          'id' => $machine_name,
          'name' => $this->application->getSiteName(),
          'base_url' => $this->application->getSiteBaseUrl(),
          'version' => $this->application->getApplicationModuleVersion(),
          'api_id' => $this->application->getApplicationId().'-'.ApiStorage::CUSTOM_API_VERSION,
      ])
      ->execute()
      ->getItem();

        $this->application->setSiteId($machine_name);

        return $machine_name;
    }

    /**
     * Verify that the site ID is valid. This requires the base URL of the site to
     * match the base URL stored in the Sync Core. If people deploy database
     * updates for example, the site will think it's another site and things
     * go south real quick. So we verify that the site ID and site URL are in
     * sync before we export any configuration. If they don't match, the user
     * must decide whether to register a new site or forcibly overwrite the
     * existing base URL.
     *
     * @return array|null
     */
    public function verifySiteId()
    {
        try {
            $site = $this
        ->storage->getInstanceStorage()
        ->getItem($this->application->getSiteId())
        ->execute()
        ->getItem();

            // No match: Warn user and don't export configuration.
            if ($site['base_url'] !== $this->application->getSiteBaseUrl()) {
                return [
                    $this->application->getSiteId() => $site['base_url'],
                ];
            }
        }
        // Ignore "not found" as we're just about to export the configuration for
        // the first time then.
        catch (NotFoundException $e) {
        }

        $sites = $this
      ->storage->getInstanceStorage()
      ->listItems()
      ->setCondition(
        ParentCondition
          ::all()
          ->add(
            DataCondition::equal('base_url', $this->application->getSiteBaseUrl())
          )
          ->add(
            DataCondition::notEqual('id', $this->application->getSiteId())
          )
      )
      ->execute()
      ->getAll();

        // Another ID is already used for this base URL.
        if ($sites) {
            return [
                $sites[0]['id'] => $sites[0]['base_url'],
            ];
        }

        // All good.
        return null;
    }

    /**
     * {@inheritdoc}
     */
    public function getSitesWithDifferentEntityTypeVersion(string $pool_id, string $entity_type, string $bundle, string $target_version)
    {
        /**
         * @var \EdgeBox\SyncCore\V1\Storage\ConnectionStorage $connectionStorage
         */
        $connectionStorage = $this
      ->storage->getConnectionStorage();

        $items = $connectionStorage->listItems()
      ->setCondition(
        ParentCondition::all()
          ->add(DataCondition::startsWith(ConnectionStorage::PROPERTY_ID, $this->application->getApplicationId().'-'.$pool_id.'-'))
          ->add(DataCondition::endsWith(ConnectionStorage::PROPERTY_ID, '-'.$entity_type.'-'.$bundle))
      )
      ->orderBy('id')
      ->getDetails()
      ->execute()
      ->getAll();

        $result = [];

        $same_version_sites = [];
        $other_version_sites = [];
        $sites = [];

        /**
         * @var \EdgeBox\SyncCore\V1\Storage\EntityTypeStorage $entityTypeStorage
         */
        $entityTypeStorage = $this
      ->storage->getEntityTypeStorage();

        foreach ($items as $item) {
            $version = preg_replace('@^.+-([^-]+)$@', '$1', $item['entity_type']['id']);
            $site_id = preg_replace('@^drupal-'.$pool_id.'-(.+)-'.$entity_type.'-.+$@', '$1', $item['id']);

            if (InstanceStorage::POOL_SITE_ID == $site_id) {
                continue;
            }

            if ($site_id == $this->application->getSiteMachineName()) {
                if ($version == $target_version) {
                    $sites[$site_id] = $item;
                }
                continue;
            }

            $sites[$site_id] = $item;

            if ($target_version == $version) {
                $same_version_sites[] = $site_id;
            } else {
                $other_version_sites[] = $site_id;
            }
        }

        if (!isset($sites[$this->application->getSiteMachineName()])) {
            return $result;
        }

        $other_version_sites = array_diff($other_version_sites, $same_version_sites);

        $this_entity_type = $entityTypeStorage
      ->getItem($sites[$this->application->getSiteMachineName()]['entity_type']['id'])
      ->execute()
      ->getItem();

        foreach ($other_version_sites as $site_id) {
            $item = $sites[$site_id];

            $data = $entityTypeStorage
        ->getItem($item['entity_type']['id'])
        ->execute()
        ->getItem();

            $result[$site_id] = $this->getEntityTypeDiff($this_entity_type, $data);
        }

        return $result;
    }

    /**
     * Get a list of fields that either the remote site or local site is missing
     * in comparison.
     *
     * @param array $mine
     * @param array $theirs
     *
     * @return array
     */
    protected function getEntityTypeDiff($mine, $theirs)
    {
        $result = [];

        foreach ($mine['new_properties'] as $name => $type) {
            if (isset($theirs['new_properties'][$name])) {
                continue;
            }

            $result['remote_missing'][] = $name;
        }

        foreach ($theirs['new_properties'] as $name => $type) {
            if (isset($mine['new_properties'][$name])) {
                continue;
            }

            $result['local_missing'][] = $name;
        }

        return $result;
    }

    /**
     * {@inheritdoc}
     */
    public function getReservedPropertyNames()
    {
        return [
            'source',
            'source_id',
            'source_connection_id',
            'preview',
            'url',
            'apiu_translation',
            'metadata',
            'embed_entities',
            'title',
            'created',
            'changed',
            'uuid',
            'menu_link',
        ];
    }
}
