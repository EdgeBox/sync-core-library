<?php

namespace EdgeBox\SyncCore\V2\Syndication;

use EdgeBox\SyncCore\Interfaces\IApplicationInterface;
use EdgeBox\SyncCore\Interfaces\Syndication\IEntityReference;
use EdgeBox\SyncCore\Interfaces\Syndication\IPullOperation;
use EdgeBox\SyncCore\V2\Configuration\DefineEntityType;
use EdgeBox\SyncCore\V2\Raw\Model\CreateRemoteEntityRevisionDto;
use EdgeBox\SyncCore\V2\Raw\Model\DeleteRemoteEntityRevisionDto;
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
     * @var DeleteRemoteEntityRevisionDto|CreateRemoteEntityRevisionDto|RemoteEntityEmbed
     */
    protected $dto;

    /**
     * @var PullOperation|null
     */
    protected $parentPullOperation;

    /**
     * @var CreateRemoteEntityRevisionDto[]
     */
    protected $translations;

    /**
     * PushSingle constructor.
     * 
     * @param SyncCore $core
     * @param RemoteEntityEmbed|array $body
     * @param PullOperation|null $parentPullOperation
     */
    public function __construct(SyncCore $core, $body, bool $delete, ?PullOperation $parentPullOperation=NULL)
    {
        $this->core = $core;
        $this->translations = [];

        if($delete) {
            // Turn nested arrays into objects.
            $body = json_decode(json_encode($body));
            $this->dto = ObjectSerializer::deserialize($body, DeleteRemoteEntityRevisionDto::class, []);
        }
        elseif($body instanceof RemoteEntityEmbed) {
            $this->dto = $body;
            $this->parentPullOperation = $parentPullOperation;
        }
        else {
            // Turn nested arrays into objects.
            $body = json_decode(json_encode($body));
            $this->dto = ObjectSerializer::deserialize($body, CreateRemoteEntityRevisionDto::class, []);

            $translations = $this->dto->getTranslations();
            if ($translations) {
                foreach ($translations as $translation_dto) {
                    $language = $translation_dto->getLanguage();
                    $this->translations[$language] = $translation_dto;
                }
            }
        }
    }

    /**
     * @return string[]
     */
    public function getPoolIds()
    {
        return $this->dto->getPoolMachineNames();
    }

    /**
     * @return string
     */
    public function getEntityTypeNamespaceMachineName()
    {
        return $this->dto->getEntityTypeByMachineName()->getNamespaceMachineName();
    }

    /**
     * @return string
     */
    public function getEntityTypeMachineName()
    {
        return $this->dto->getEntityTypeByMachineName()->getMachineName();
    }

    public function getEntityTypeVersionId()
    {
        return $this->dto->getEntityTypeByMachineName()->getVersionId();
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
        if($this->parentPullOperation) {
            return $this->parentPullOperation->getSourceUrl();
        }
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
                // Turn objects into arrays.
                return json_decode(json_encode($property->getValue()), true);
            }
        }

        return null;
    }

    /**
     * {@inheritdoc}
     */
    public function loadReference(array $data)
    {
        if($this->parentPullOperation) {
            return $this->parentPullOperation->loadReference($data);
        }

        // Need to turn arrays into objects.
        $data = json_decode(json_encode($data));
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
                break;
            }
        }

        /*if(!$embed) {
            throw new InternalContentSyncError("Embedded entity not found: ".$referenceDto->getEntityTypeNamespaceMachineName().".".$referenceDto->getEntityTypeMachineName()." ".$candidate->getRemoteUuid()." / ".$referenceDto->getRemoteUniqueId()." (".$referenceDto->getLanguage().")");
        }*/

        return new class($this->core, $referenceDto, $this, $embed) implements IEntityReference {
            /**
             * @var SyncCore
             */
            protected $core;
            /**
             * @var RemoteEntityDependency
             */
            protected $dto;
            /**
             * @var PullOperation
             */
            protected $pullOperation;
            /**
             * @var RemoteEntityEmbed|null
             */
            protected $embed;

            /**
             * constructor.
             */
            public function __construct(SyncCore $core, RemoteEntityDependency $dto, PullOperation $pullOperation, ?RemoteEntityEmbed $embed = null)
            {
                $this->core = $core;
                $this->dto = $dto;
                $this->pullOperation = $pullOperation;
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

                if(!$details) {
                    return [];
                }

                // Turn objects into arrays.
                return json_decode(json_encode($details), TRUE);
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
            public function getPoolIds()
            {
                return $this->dto->getPoolMachineNames();
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
                $this->embed,
                FALSE,
                $this->pullOperation);
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
        if($this->dto instanceof DeleteRemoteEntityRevisionDto) {
            return [];
        }

        $data = $this->dto->jsonSerialize();

        // Turn objects into arrays
        $data = json_decode(json_encode($data), true);

        $data['viewUrl'] = $entity_deep_link;

        return $data;
    }
}
