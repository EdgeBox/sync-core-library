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
use EdgeBox\SyncCore\V2\Raw\Model\RemoteEntityEmbedDraft;
use EdgeBox\SyncCore\V2\Raw\Model\RemoteEntityEmbedRootDraft;
use EdgeBox\SyncCore\V2\Raw\Model\RemoteEntityRootEmbed;
use EdgeBox\SyncCore\V2\Raw\ObjectSerializer;
use EdgeBox\SyncCore\V2\SyncCore;

class PullOperation implements IPullOperation
{
    /**
     * @var SyncCore
     */
    protected $core;

    /**
     * @var CreateRemoteEntityRevisionDto|DeleteRemoteEntityRevisionDto|RemoteEntityEmbed|RemoteEntityEmbedDraft|RemoteEntityEmbedRootDraft|RemoteEntityRootEmbed
     */
    protected $dto;

    /**
     * @var null|PullOperation
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
     * Cached file entity.
     *
     * @var null|FileEntity
     */
    protected $fileEntity;

    /**
     * PullOperation constructor.
     *
     * @param array|RemoteEntityEmbed|RemoteEntityEmbedDraft|RemoteEntityEmbedRootDraft|RemoteEntityRootEmbed $body
     */
    public function __construct(SyncCore $core, $body, bool $delete, ?PullOperation $parentPullOperation = null)
    {
        $this->core = $core;
        $this->translations = [];

        if ($delete) {
            // Turn nested arrays into objects.
            $body = json_decode(json_encode($body));
            $this->dto = @ObjectSerializer::deserialize($body, DeleteRemoteEntityRevisionDto::class, []);
        } elseif ($body instanceof RemoteEntityEmbed || $body instanceof RemoteEntityEmbedDraft || $body instanceof RemoteEntityRootEmbed || $body instanceof RemoteEntityEmbedRootDraft) {
            $this->dto = $body;
            $this->parentPullOperation = $parentPullOperation;

            if ($body instanceof RemoteEntityRootEmbed || $body instanceof RemoteEntityEmbedRootDraft) {
                $translations = $this->dto->getTranslations();
                if ($translations) {
                    foreach ($translations as $translation_dto) {
                        $language = $translation_dto->getLanguage();
                        $this->translations[$language] = $translation_dto;
                    }
                }
            }
        } else {
            // Turn nested arrays into objects.
            $body = json_decode(json_encode($body));

            // Must call with @ as otherwise it will produce a notice
            // Warning: settype(): Invalid type in EdgeBox\SyncCore\V2\Raw\ObjectSerializer::deserialize() (line 341 of /opt/library/src/V2/Raw/ObjectSerializer.php)
            /**
             * @var CreateRemoteEntityRevisionDto $dto
             */
            $dto = @ObjectSerializer::deserialize($body, CreateRemoteEntityRevisionDto::class, []);
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
        }
        if ($this->dto instanceof RemoteEntityEmbed || $this->dto instanceof RemoteEntityEmbedDraft || $this->dto instanceof RemoteEntityRootEmbed || $this->dto instanceof RemoteEntityEmbedRootDraft) {
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
        }
        if ($this->dto instanceof RemoteEntityEmbed || $this->dto instanceof RemoteEntityEmbedDraft || $this->dto instanceof RemoteEntityRootEmbed || $this->dto instanceof RemoteEntityEmbedRootDraft) {
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
    public function getVersionId()
    {
        if ($this->dto instanceof RemoteEntityEmbed || $this->dto instanceof RemoteEntityEmbedDraft || $this->dto instanceof RemoteEntityRootEmbed || $this->dto instanceof RemoteEntityEmbedRootDraft || $this->dto instanceof CreateRemoteEntityRevisionDto) {
            return $this->dto->getVersionId() ?? null;
        }

        return null;
    }

    /**
     * {@inheritdoc}
     */
    public function getSourceUrl(?string $language = null)
    {
        if ($this->dto instanceof DeleteRemoteEntityRevisionDto) {
            return '';
        }

        $dto = $language && isset($this->translations[$language]) ? $this->translations[$language] : $this->dto;

        if ($dto && method_exists($dto, 'getViewUrl')) {
            $url = $dto->getViewUrl();
            if ($url) {
                return $url;
            }
        }

        if ($this->parentPullOperation) {
            return $this->parentPullOperation->getSourceUrl($language);
        }

        return '';
    }

    /**
     * {@inheritdoc}
     */
    public function getUsedTranslationLanguages()
    {
        if ($this->dto instanceof DeleteRemoteEntityRevisionDto) {
            return [];
        }

        if ($this->dto instanceof CreateRemoteEntityRevisionDto && !empty($this->dto->getAllLanguages())) {
            $languages = $this->dto->getAllLanguages();
            // Exclude the root language.
            return array_diff($languages, [$this->dto->getLanguage()]);
        }

        return array_keys($this->translations);
    }

    /**
     * {@inheritdoc}
     */
    public function getProperty(string $name, ?string $language = null)
    {
        if ($this->dto instanceof DeleteRemoteEntityRevisionDto) {
            return null;
        }
        $properties = $language && $language !== $this->dto->getLanguage() ? $this->translations[$language]->getProperties() : $this->dto->getProperties();
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
        if ($this->dto instanceof DeleteRemoteEntityRevisionDto) {
            return null;
        }

        $embedded = $this->dto->getEmbed();
        if (is_array($embedded)) {
            foreach ($embedded as $index => $embed) {
                if (in_array($index, $this->processedEmbeds)) {
                    continue;
                }

                $this->processedEmbeds[] = $index;

                return new PullOperation($this->core, $embed, false, $this);
            }
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
        $referenceDto = @ObjectSerializer::deserialize($data, RemoteEntityDependency::class);

        $embeds = $this->dto->getEmbed();
        $embed = null;
        $embedIndex = null;
        if (is_array($embeds)) {
            for ($i = 0; $i < count($embeds); ++$i) {
                $candidate = $embeds[$i];
                if ($candidate->getEntityTypeNamespaceMachineName() === $referenceDto->getEntityTypeNamespaceMachineName()
                    && $candidate->getLanguage() === $referenceDto->getLanguage()
            && $candidate->getEntityTypeMachineName() === $referenceDto->getEntityTypeMachineName()
            && $candidate->getRemoteUuid() === $referenceDto->getRemoteUuid()
            && $candidate->getRemoteUniqueId() === $referenceDto->getRemoteUniqueId()) {
                    $embed = $candidate;
                    $embedIndex = $i;

                    break;
                }
            }
        }

        /*if(!$embed) {
            throw new InternalContentSyncError("Embedded entity not found: ".$referenceDto->getEntityTypeNamespaceMachineName().".".$referenceDto->getEntityTypeMachineName()." ".$candidate->getRemoteUuid()." / ".$referenceDto->getRemoteUniqueId()." (".$referenceDto->getLanguage().")");
        }*/

        /**
         * @var RemoteEntityEmbed|RemoteEntityEmbedDraft|RemoteEntityRootEmbed|RemoteEntityRootEmbedDraft $embed
         */

        return new PullOperationEmbed($this->core, $referenceDto, $this, $embedIndex, $embed);
    }

    /**
     * {@inheritdoc}
     */
    public function loadReferencesByProperties(array $properties)
    {
        $result = [];

        if ($this->parentPullOperation) {
            return $this->parentPullOperation->loadReferencesByProperties($properties);
        }

        $embeds = $this->dto->getEmbed();
        if (is_array($embeds)) {
            for ($i = 0; $i < count($embeds); ++$i) {
                $candidate = $embeds[$i];
                $candidate_properties = $candidate->getProperties();
                $all_match = true;
                foreach ($properties as $name => $value) {
                    $matches = false;
                    foreach ($candidate_properties as $property) {
                        if ($property->getName() === $name) {
                            $prop_value = $property->getValue();
                            $check_value = $value;
                            while (is_array($check_value)) {
                                $key = array_key_first($check_value);
                                $prop_value = ((array) $prop_value)[$key] ?? null;
                                $check_value = $check_value[$key];
                            }
                            if ($prop_value === $check_value) {
                                $matches = true;

                                break;
                            }
                        }
                    }
                    if (!$matches) {
                        $all_match = false;

                        break;
                    }
                }
                if ($all_match) {
                    /**
                     * @var RemoteEntityDependency $referenceDto
                     */
                    $referenceDto = @ObjectSerializer::deserialize($candidate->__toString(), RemoteEntityDependency::class);
                    $result[] = new PullOperationEmbed($this->core, $referenceDto, $this, $i, $candidate);
                }
            }
        }

        return $result;
    }

    /**
     * {@inheritdoc}
     */
    public function loadFile()
    {
        $file_entity = $this->loadFileEntity();

        return $file_entity ? new File($file_entity) : null;
    }

    /**
     * {@inheritdoc}
     */
    public function downloadFile()
    {
        $file = $this->loadFile();

        return $file ? $file->download() : null;
    }

    /**
     * {@inheritdoc}
     */
    public function getName(?string $language = null)
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

    /**
     * Load the Sync Core file object.
     *
     * @return null|FileEntity
     */
    protected function loadFileEntity()
    {
        if ($this->fileEntity) {
            return $this->fileEntity;
        }

        $reference = $this->getProperty(DefineEntityType::FILE_PROPERTY_NAME);
        if (empty($reference) || empty($reference['id'])) {
            return null;
        }

        $request = $this->core->getClient()->fileControllerItemRequest($reference['id']);
        /**
         * @var FileEntity $file
         */
        $this->fileEntity = $this->core->sendToSyncCoreAndExpect($request, FileEntity::class, IApplicationInterface::SYNC_CORE_PERMISSIONS_CONTENT);

        return $this->fileEntity;
    }
}
