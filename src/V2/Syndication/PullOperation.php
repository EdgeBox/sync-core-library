<?php

namespace EdgeBox\SyncCore\V2\Syndication;

use EdgeBox\SyncCore\Interfaces\IApplicationInterface;
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
     * @var int[]
     *
     * The indices of the embeds that were already processed during the pull operation.
     * This is used to get all non-processed entities after the root pull to also pull
     * them separately, e.g. menu items.
     */
    protected $processedEmbeds = [];

    /**
     * PushSingle constructor.
     *
     * @param RemoteEntityEmbed|array $body
     */
    public function __construct(SyncCore $core, $body, bool $delete, ?PullOperation $parentPullOperation = null)
    {
        $this->core = $core;
        $this->translations = [];

        if ($delete) {
            // Turn nested arrays into objects.
            $body = json_decode(json_encode($body));
            $this->dto = ObjectSerializer::deserialize($body, DeleteRemoteEntityRevisionDto::class, []);
        } elseif ($body instanceof RemoteEntityEmbed) {
            $this->dto = $body;
            $this->parentPullOperation = $parentPullOperation;
        } else {
            // Turn nested arrays into objects.
            $body = json_decode(json_encode($body));

            /**
             * @var CreateRemoteEntityRevisionDto $dto
             */
            $dto = ObjectSerializer::deserialize($body, CreateRemoteEntityRevisionDto::class, []);
            $this->dto = $dto;

            $translations = $this->dto->getTranslations();
            if ($translations) {
                foreach ($translations as $translation_dto) {
                    $language = $translation_dto->getLanguage();
                    $this->translations[$language] = $translation_dto;
                }
            }
        }
    }

    public function isEmbedded($type, $entity_uuid)
    {
        $embeds = $this->dto->getEmbed();
        foreach ($embeds as $embed) {
            if ($embed->getEntityTypeNamespaceMachineName() === $type && $embed->getRemoteUuid() === $entity_uuid) {
                return true;
            }
        }

        return false;
    }

    /**
     * @return string[]
     */
    public function getPoolIds()
    {
        if ($this->dto instanceof DeleteRemoteEntityRevisionDto) {
            return [];
        }

        return $this->dto->getPoolMachineNames();
    }

    /**
     * @return string
     */
    public function getEntityTypeNamespaceMachineName()
    {
        if ($this->dto instanceof DeleteRemoteEntityRevisionDto) {
            return $this->dto->getEntityTypeNamespaceMachineName();
        } elseif ($this->dto instanceof RemoteEntityEmbed) {
            return $this->dto->getEntityTypeNamespaceMachineName();
        }

        return $this->dto->getEntityTypeByMachineName()->getNamespaceMachineName();
    }

    /**
     * @return string
     */
    public function getEntityTypeMachineName()
    {
        if ($this->dto instanceof DeleteRemoteEntityRevisionDto) {
            return $this->dto->getEntityTypeMachineName();
        } elseif ($this->dto instanceof RemoteEntityEmbed) {
            return $this->dto->getEntityTypeMachineName();
        }

        return $this->dto->getEntityTypeByMachineName()->getMachineName();
    }

    public function getEntityTypeVersionId()
    {
        if ($this->dto instanceof DeleteRemoteEntityRevisionDto) {
            return '';
        }

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
        if ($this->dto instanceof DeleteRemoteEntityRevisionDto) {
            return '';
        }
        if ($this->parentPullOperation) {
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
        if ($this->dto instanceof DeleteRemoteEntityRevisionDto) {
            return null;
        }
        $properties = $language ? $this->translations[$language]->getProperties() : $this->dto->getProperties();
        foreach ($properties as $property) {
            if ($property->getName() === $name) {
                // Turn objects into arrays.
                return json_decode(json_encode($property->getValue()), true);
            }
        }

        return null;
    }

    public function embedProcessed(int $index)
    {
        if ($this->parentPullOperation) {
            $this->parentPullOperation->embedProcessed($index);

            return;
        }
        $this->processedEmbeds[] = $index;
    }

    public function getNextUnprocessedEmbed()
    {
        foreach ($this->dto->getEmbed() as $index => $embed) {
            if (in_array($index, $this->processedEmbeds)) {
                continue;
            }

            $this->processedEmbeds[] = $index;

            return new PullOperation($this->core, $embed, false, $this);
        }

        return null;
    }

    /**
     * {@inheritdoc}
     */
    public function loadReference(array $data)
    {
        if ($this->parentPullOperation) {
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
        $embedIndex = null;
        for ($i = 0; $i < count($embeds); ++$i) {
            $candidate = $embeds[$i];
            if ($candidate->getEntityTypeNamespaceMachineName() === $referenceDto->getEntityTypeNamespaceMachineName() &&
                $candidate->getLanguage() === $referenceDto->getLanguage() &&
          $candidate->getEntityTypeMachineName() === $referenceDto->getEntityTypeMachineName() &&
          $candidate->getRemoteUuid() === $referenceDto->getRemoteUuid() &&
          $candidate->getRemoteUniqueId() === $referenceDto->getRemoteUniqueId()) {
                $embed = $candidate;
                $embedIndex = $i;
                break;
            }
        }

        /*if(!$embed) {
            throw new InternalContentSyncError("Embedded entity not found: ".$referenceDto->getEntityTypeNamespaceMachineName().".".$referenceDto->getEntityTypeMachineName()." ".$candidate->getRemoteUuid()." / ".$referenceDto->getRemoteUniqueId()." (".$referenceDto->getLanguage().")");
        }*/

        /**
         * @var \EdgeBox\SyncCore\V2\Raw\Model\RemoteEntityEmbed $embed
         */

        return new PullOperationEmbed($this->core, $referenceDto, $this, $embedIndex, $embed);
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
        if ($this->dto instanceof DeleteRemoteEntityRevisionDto) {
            return [];
        }

        $data = $this->dto->jsonSerialize();

        // Turn objects into arrays
        $data = json_decode(json_encode($data), true);

        if ($entity_deep_link) {
            $data['viewUrl'] = $entity_deep_link;
        }

        return $data;
    }
}
