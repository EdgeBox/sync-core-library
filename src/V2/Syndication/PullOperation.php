<?php

namespace EdgeBox\SyncCore\V2\Syndication;

use EdgeBox\SyncCore\Interfaces\IApplicationInterface;
use EdgeBox\SyncCore\Interfaces\Syndication\IEntityReference;
use EdgeBox\SyncCore\Interfaces\Syndication\IPullOperation;
use EdgeBox\SyncCore\V2\Configuration\DefineEntityType;
use EdgeBox\SyncCore\V2\Raw\Model\CreateRemoteEntityRevisionDto;
use EdgeBox\SyncCore\V2\Raw\Model\FileEntity;
use EdgeBox\SyncCore\V2\Raw\Model\RemoteEntityDependency;
use EdgeBox\SyncCore\V2\Raw\Model\RemoteEntityEmbed;
use EdgeBox\SyncCore\V2\Raw\ObjectSerializer;
use EdgeBox\SyncCore\V2\SyncCore;

class PullOperation implements IPullOperation
{
    /**
     * @var SyncCore
     */
    protected $core;

    /**
     * @var string
     */
    protected $type;

    /**
     * @var string
     */
    protected $bundle;

    /**
     * @var CreateRemoteEntityRevisionDto
     */
    protected $dto;

    /**
     * @var CreateRemoteEntityRevisionDto[]
     */
    protected $translations;

    /**
     * PushSingle constructor.
     */
    public function __construct(SyncCore $core, string $type, string $bundle, array $body)
    {
        $this->core = $core;
        $this->type = $type;
        $this->bundle = $bundle;
        $this->dto = new CreateRemoteEntityRevisionDto($body);
        $this->translations = [];

        $translations = $this->dto->getTranslations();
        if ($translations) {
            foreach ($translations as $translation_dto) {
                $language = $translation_dto->getLanguage();
                $this->translations[$language] = $translation_dto;
            }
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getId()
    {
        return $this->dto->getRemoteUniqueId() ?? $this->dto->getRemoteUuid();
    }

    /**
     * {@inheritdoc}
     */
    public function getUuid()
    {
        return $this->dto->getRemoteUuid();
    }

    /**
     * {@inheritdoc}
     */
    public function getSourceUrl()
    {
        return $this->dto->getViewUrl();
    }

    /**
     * {@inheritdoc}
     */
    public function getUsedTranslationLanguages()
    {
        return array_keys($this->translations);
    }

    /**
     * {@inheritdoc}
     */
    public function getProperty(string $name, $language = null)
    {
        $properties = $language ? $this->translations[$language]->getProperties() : $this->dto->getProperties();
        foreach ($properties as $property) {
            if ($property->getName() === $name) {
                return $property->getValue();
            }
        }

        return null;
    }

    /**
     * {@inheritdoc}
     */
    public function loadReference(array $data)
    {
        /**
         * @var RemoteEntityDependency $referenceDto
         */
        $referenceDto = ObjectSerializer::deserialize($data, RemoteEntityDependency::class);

        $embeds = $this->dto->getEmbed();
        $embed = null;
        for ($i = 0; $i < count($embeds); ++$i) {
            $candidate = $embeds[$i];
            if ($candidate->getEntityTypeNamespaceMachineName() === $referenceDto->getEntityTypeNamespaceMachineName() &&
                $candidate->getLanguage() === $referenceDto->getLanguage() &&
          $candidate->getEntityTypeMachineName() === $referenceDto->getEntityTypeMachineName() &&
          $candidate->getRemoteUuid() === $referenceDto->getRemoteUuid() &&
          $candidate->getRemoteUniqueId() === $referenceDto->getRemoteUniqueId()) {
                $embed = $candidate;
            }
        }

        return new class($this->core, $referenceDto, $embed) implements IEntityReference {
            /**
             * @var SyncCore
             */
            protected $core;
            /**
             * @var RemoteEntityDependency
             */
            protected $dto;
            /**
             * @var RemoteEntityEmbed|null
             */
            protected $embed;

            /**
             * constructor.
             */
            public function __construct(SyncCore $core, RemoteEntityDependency $dto, ?RemoteEntityEmbed $embed = null)
            {
                $this->core = $core;
                $this->dto = $dto;
                $this->embed = $embed;
            }

            /**
             * {@inheritdoc}
             */
            public function getDetails()
            {
                /**
                 * @var array|null $details
                 */
                $details = $this->dto->getReferenceDetails();

                return $details;
            }

            /**
             * {@inheritdoc}
             */
            public function getId()
            {
                return $this->dto->getRemoteUniqueId();
            }

            /**
             * {@inheritdoc}
             */
            public function getUuid()
            {
                return $this->dto->getRemoteUuid();
            }

            /**
             * {@inheritdoc}
             */
            public function getType()
            {
                return $this->dto->getEntityTypeNamespaceMachineName();
            }

            /**
             * {@inheritdoc}
             */
            public function getBundle()
            {
                return $this->dto->getEntityTypeMachineName();
            }

            /**
             * {@inheritdoc}
             */
            public function getVersion()
            {
                return $this->dto->getEntityTypeVersion();
            }

            /**
             * {@inheritdoc}
             */
            public function getName()
            {
                return $this->dto->getName();
            }

            /**
             * {@inheritdoc}
             */
            public function getPoolId()
            {
                // TODO: Support multiple.
                return $this->dto->getPoolMachineNames()[0];
            }

            /**
             * {@inheritdoc}
             */
            public function isEmbedded()
            {
                return (bool) $this->embed;
            }

            /**
             * {@inheritdoc}
             */
            public function getEmbeddedEntity()
            {
                return new PullOperation(
            $this->core,
            $this->getType(),
            $this->getBundle(),
            $this->embed->jsonSerialize());
            }
        };
    }

    /**
     * {@inheritdoc}
     */
    public function downloadFile()
    {
        $reference = $this->getProperty(DefineEntityType::FILE_PROPERTY_NAME);
        if (empty($reference) || empty($reference['id'])) {
            return null;
        }

        $request = $this->core->getClient()->fileControllerItemRequest($reference['id']);
        /**
         * @var FileEntity $file
         */
        $file = $this->core->sendToSyncCoreAndExpect($request, FileEntity::class, IApplicationInterface::SYNC_CORE_PERMISSIONS_CONTENT);

        if (empty($file->getDownloadUrl())) {
            return null;
        }

        return file_get_contents($file->getDownloadUrl());
    }

    /**
     * {@inheritdoc}
     */
    public function getName($language = null)
    {
        $dto = $language ? $this->translations[$language] : $this->dto;

        return $dto->getName();
    }

    /**
     * {@inheritdoc}
     */
    public function getResponseBody(?string $entity_deep_link)
    {
        $data = $this->dto->jsonSerialize();
        $data['viewUrl'] = $entity_deep_link;

        return $data;
    }
}
