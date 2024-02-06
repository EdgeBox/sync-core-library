<?php

namespace EdgeBox\SyncCore\V2\Syndication;

use EdgeBox\SyncCore\Exception\InternalContentSyncError;
use EdgeBox\SyncCore\Interfaces\IApplicationInterface;
use EdgeBox\SyncCore\Interfaces\Syndication\IPushSingle;
use EdgeBox\SyncCore\V2\Configuration\DefineEntityType;
use EdgeBox\SyncCore\V2\Helper;
use EdgeBox\SyncCore\V2\Raw\Model\CreateRemoteEntityRevisionDto;
use EdgeBox\SyncCore\V2\Raw\Model\DeleteRemoteEntityRevisionDto;
use EdgeBox\SyncCore\V2\Raw\Model\EntityTypeVersionReference;
use EdgeBox\SyncCore\V2\Raw\Model\FileType;
use EdgeBox\SyncCore\V2\Raw\Model\RemoteEntityDependency;
use EdgeBox\SyncCore\V2\Raw\Model\RemoteEntityEmbedDraft;
use EdgeBox\SyncCore\V2\Raw\Model\RemoteEntityEmbedRootDraft;
use EdgeBox\SyncCore\V2\Raw\Model\RemoteEntityPropertyDraft;
use EdgeBox\SyncCore\V2\Raw\Model\SiteApplicationType;
use EdgeBox\SyncCore\V2\Raw\ObjectSerializer;
use EdgeBox\SyncCore\V2\SerializableWithSyncCoreReference;
use EdgeBox\SyncCore\V2\SyncCore;

class PushSingle extends SerializableWithSyncCoreReference implements IPushSingle
{
    public const RESERVED_PROPERTY_NAMES = [];

    public const DEPENDENCY_TYPE_OPTIONAL = 0;
    public const DEPENDENCY_TYPE_REQUIRED = 1;
    public const DEPENDENCY_TYPE_EMBED = 2;

    /**
     * @var array
     *            A list of all entities that were pushed in the current request
     *            and a unique hash of their current value. Used in serialized reference
     *            fields to ensure that even if only a child entity has changed, the parent
     *            entity is also syndicated. Format:
     *            [$entity_type][$entity_uuid] = (string)$hash.
     */
    public static $serializedEntities = [];

    /**
     * @var CreateRemoteEntityRevisionDto
     */
    protected $dto;

    /**
     * @var CreateRemoteEntityRevisionDto[]
     */
    protected $translations;

    /**
     * @var SyncCore
     */
    protected $core;

    /**
     * @var bool
     */
    protected $is_dependency;

    /**
     * @var bool
     */
    protected $is_deletion;

    /**
     * PushSingle constructor.
     */
    public function __construct(SyncCore $core, string $flowMachineName, string $namespaceMachineName, string $machineName, string $versionId, string $root_language, ?string $uuid, ?string $unique_id)
    {
        $this->core = $core;

        $typeReference = new EntityTypeVersionReference();
        $typeReference->setNamespaceMachineName($namespaceMachineName);
        $typeReference->setMachineName($machineName);
        $typeReference->setVersionId($versionId);

        $this->dto = $this->initEmptyDto($root_language, $flowMachineName, $typeReference, $uuid, $unique_id);

        $this->translations = [];

        self::$serializedEntities[$namespaceMachineName][$uuid] = &$this->dto;
    }

    /**
     * {@inheritdoc}
     */
    public function getEntityHash()
    {
        return self::getReferencedEntityHash(
            $this->dto->getEntityTypeByMachineName()->getNamespaceMachineName(),
            $this->dto->getRemoteUuid()
        );
    }

    /**
     * @return string
     */
    public static function getReferencedEntityHash(string $type, string $uuid)
    {
        if (isset(self::$serializedEntities[$type][$uuid])) {
            return Helper::getSerializedEntityHash(self::$serializedEntities[$type][$uuid]);
        }

        return 'unknown';
    }

    /**
     * {@inheritdoc}
     */
    public function toPool(string $pool_id)
    {
        $pools = $this->dto->getPoolMachineNames();
        if (!$pools) {
            $pools = [];
        }
        $pools[] = $pool_id;
        $this->dto->setPoolMachineNames($pools);

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function asDependency(bool $set)
    {
        $this->is_dependency = $set;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function delete(bool $set)
    {
        $this->is_deletion = $set;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getData()
    {
        return $this->dto->jsonSerialize();
    }

    /**
     * Get the definition for a referenced entity that should be pushed /
     * embedded as well.
     *
     * @param string     $type
     *                                       The entity type of the referenced entity
     * @param string     $bundle
     *                                       The bundle of the referenced entity
     * @param string     $uuid
     *                                       The UUID of the referenced entity
     * @param string     $id
     *                                       The ID of the entity, if it should be kept across sites
     * @param string     $version
     *                                       The version hash of the entity type definition
     * @param string[]   $pool_machine_names
     *                                       The pool ID to add the entity for. Can be different to the current pool
     *                                       ID.
     * @param null|array $details
     *                                       Additional details you would like to push
     * @param null|string $view_url
     *                                       The canonical URL / deep link to the entity on the source site, if any
     *
     * @return array|object the definition to be pushed
     */
    public function getEntityReferenceDto(string $type, string $bundle, ?string $uuid, ?string $id, string $version, array $pool_machine_names, string $language, ?string $name, $details = null, ?string $view_url = null)
    {
        $entityReference = new RemoteEntityDependency();

        $entityReference->setRemoteUuid($uuid);
        $entityReference->setRemoteUniqueId($id);
        $entityReference->setLanguage($language);

        $entityReference->setEntityTypeNamespaceMachineName($type);
        $entityReference->setEntityTypeMachineName($bundle);
        $entityReference->setEntityTypeVersion($version);

        $entityReference->setPoolMachineNames($pool_machine_names);

        if ($name) {
            $entityReference->setName($name);
        }

        if ($details) {
            /**
             * @var object $details
             */
            $entityReference->setReferenceDetails($details);
        }

        if ($view_url) {
            $entityReference->setViewUrl($view_url);
        }

        return $entityReference;
    }

    /**
     * Embed an entity by its properties.
     *
     * @param string $type
     *                                   {@see SyncIntent::getEmbedEntityDefinition}
     * @param string $bundle
     *                                   {@see SyncIntent::getEmbedEntityDefinition}
     * @param string $uuid
     *                                   {@see SyncIntent::getEmbedEntityDefinition}
     * @param string $id
     *                                   The ID of the entity, if it should be kept across sites
     * @param string $version
     *                                   {@see SyncIntent::getEmbedEntityDefinition}
     * @param array  $pool_machine_names
     *                                   {@see SyncIntent::getEmbedEntityDefinition}
     * @param null   $details
     *                                   {@see SyncIntent::getEmbedEntityDefinition}
     * @param null|string $view_url
     *                                   The canonical URL / deep link to the entity on the source site, if any
     *
     * @return array The definition you can store via {@see SyncIntent::setField}
     *
     * @see SyncIntent::getEmbedEntityDefinition
     * @see SyncIntent::embedEntity
     */
    public function addDirectDependency(
        string $type,
        string $bundle,
        ?string $uuid,
        ?string $id,
        string $version,
        array $pool_machine_names,
        string $language,
        ?string $name,
        $details = null,
        ?string $view_url = null
    ) {
        $direct_dependencies = $this->dto->getEmbed();

        if (!$direct_dependencies) {
            $direct_dependencies = [];
        } else {
            // Already included? Just return the definition then.
            foreach ($direct_dependencies as $definition) {
                if ($definition->getEntityTypeNamespaceMachineName() === $type && $definition->getEntityTypeMachineName() === $bundle && $definition->getRemoteUuid() == $uuid && $definition->getRemoteUniqueId() == $id) {
                    return $this->getEntityReferenceDto(
                        $type,
                        $bundle,
                        $uuid,
                        $id,
                        $version,
                        $pool_machine_names,
                        $language,
                        $name,
                        $details,
                        $view_url
                    );
                }
            }
        }

        $dto = $this->getEntityReferenceDto(
            $type,
            $bundle,
            $uuid,
            $id,
            $version,
            $pool_machine_names,
            $language,
            $name,
            $details,
            $view_url
        );

        $direct_dependencies[] = $dto;

        $this->dto->setDirectDependencies($direct_dependencies);

        return $dto;
    }

    public function getDto()
    {
        return $this->dto;
    }

    /**
     * {@inheritdoc}
     *
     * @param string     $id
     * @param PushSingle $embed_entity
     * @param null       $details
     *
     * @return array|RemoteEntityDependency
     */
    public function embed(string $type, string $bundle, ?string $uuid, ?string $id, string $version, IPushSingle $embed_entity, $details = null)
    {
        // Add all dependencies from the child directly to us, otherwise they'll
        // be missing on the remote site.
        foreach ($embed_entity->getDto()->getDirectDependencies() as $dependency) {
            $this->addDirectDependency(
                $dependency->getEntityTypeNamespaceMachineName(),
                $dependency->getEntityTypeMachineName(),
                $dependency->getRemoteUuid(),
                $dependency->getRemoteUniqueId(),
                $dependency->getEntityTypeVersion(),
                $dependency->getPoolMachineNames(),
                $dependency->getLanguage(),
                $dependency->getName(),
                $dependency->getReferenceDetails(),
                $dependency->getViewUrl()
            );
        }

        $embeds = $this->dto->getEmbed();
        if (!$embeds) {
            $embeds = [];
        }

        $add_embed = function ($embed) use (&$embeds) {
            foreach ($embeds as $candidate) {
                if (
                    $candidate->getEntityTypeNamespaceMachineName() == $embed->getEntityTypeNamespaceMachineName()
                    && $candidate->getEntityTypeMachineName() == $embed->getEntityTypeMachineName()
                    && $candidate->getEntityTypeVersion() == $embed->getEntityTypeVersion()
                    && $candidate->getRemoteUuid() == $embed->getRemoteUuid()
                    && $candidate->getRemoteUniqueId() == $embed->getRemoteUniqueId()
                ) {
                    // As we expect all translations to be sent, we always expect the same root language to be set.
                    if ($candidate->getLanguage() != $embed->getLanguage()) {
                        throw new InternalContentSyncError("The same entity {$candidate->getEntityTypeNamespaceMachineName()}.{$candidate->getEntityTypeMachineName()} {$candidate->getRemoteUuid()}{$candidate->getRemoteUniqueId()} was embedded twice but with a different root language. Previous root language: {$candidate->getLanguage()}. New root language: {$embed->getLanguage()}.");
                    }

                    return;
                }
            }

            $embeds[] = $embed;
        };

        // If this is nested, we already get the serialized entity from the child.
        $nested_embeds = $embed_entity->getDto()->getEmbed();
        if ($nested_embeds) {
            foreach ($nested_embeds as $embed) {
                $add_embed($embed);
            }
        }

        $copy_embed = function ($embed_dto, $previous_dto, $details = null) use (&$copy_embed) {
            /**
             * @var CreateRemoteEntityRevisionDto $previous_dto
             */
            /**
             * @var RemoteEntityEmbedDraft|RemoteEntityEmbedRootDraft $embed_dto
             */
            $embed_dto->setDirectDependencies([]);
            $embed_dto->setEntityTypeMachineName($previous_dto->getEntityTypeByMachineName()->getMachineName());
            $embed_dto->setEntityTypeNamespaceMachineName($previous_dto->getEntityTypeByMachineName()->getNamespaceMachineName());
            $embed_dto->setEntityTypeVersion($previous_dto->getEntityTypeByMachineName()->getVersionId());
            $embed_dto->setLanguage($previous_dto->getLanguage());
            $embed_dto->setRemoteUuid($previous_dto->getRemoteUuid());
            $embed_dto->setRemoteUniqueId($previous_dto->getRemoteUniqueId());
            $embed_dto->setLanguage($previous_dto->getLanguage());
            $embed_dto->setPoolMachineNames($previous_dto->getPoolMachineNames());
            $embed_dto->setReferenceDetails($details);
            $embed_dto->setName($previous_dto->getName());
            $embed_dto->setProperties($previous_dto->getProperties());
            $embed_dto->setIsTranslationRoot($previous_dto->getIsTranslationRoot());
            $embed_dto->setViewUrl($previous_dto->getViewUrl());

            if ($embed_dto instanceof RemoteEntityEmbedRootDraft) {
                $translations = $previous_dto->getTranslations();
                if ($translations) {
                    $embed_translations = [];
                    foreach ($translations as $previous_translation_dto) {
                        $embed_translation_dto = new RemoteEntityEmbedDraft();
                        $copy_embed($embed_translation_dto, $previous_translation_dto);
                        $embed_translations[] = $embed_translation_dto;
                    }
                    $embed_dto->setTranslations($embed_translations);
                }
            }
        };

        $previous_dto = $embed_entity->getDto();
        $embed_dto = new RemoteEntityEmbedRootDraft();

        $copy_embed($embed_dto, $previous_dto, $details);

        $add_embed($embed_dto);

        $this->dto->setEmbed($embeds);

        return $this->getEntityReferenceDto(
            $type,
            $bundle,
            $uuid,
            $id,
            $version,
            $embed_entity->getDto()->getPoolMachineNames(),
            $embed_entity->getDto()->getLanguage(),
            $embed_entity->getDto()->getName(),
            $details,
            $embed_entity->getDto()->getViewUrl()
        );
    }

    /**
     * {@inheritdoc}
     */
    public function addDependency(string $type, string $bundle, ?string $uuid, ?string $id, string $version, array $pool_machine_names, string $language, ?string $name, $details = null, ?string $view_url = null)
    {
        return $this->addDirectDependency(
            $type,
            $bundle,
            $uuid,
            $id,
            $version,
            $pool_machine_names,
            $language,
            $name,
            $details,
            $view_url
        );
    }

    /**
     * {@inheritdoc}
     */
    public function addReference(string $type, string $bundle, ?string $uuid, ?string $id, string $version, array $pool_machine_names, string $language, ?string $name, $details = null, ?string $view_url = null)
    {
        return $this->getEntityReferenceDto(
            $type,
            $bundle,
            $uuid,
            $id,
            $version,
            $pool_machine_names,
            $language,
            $name,
            $details,
            $view_url
        );
    }

    /**
     * {@inheritdoc}
     */
    public function setProperty(string $name, $value, $language = null)
    {
        // Will lead to a validation error if sent to the backend.
        if (null === $value) {
            return $this;
        }
        $dto = $language ? $this->getTranslation($language) : $this->dto;

        $properties = $dto->getProperties();
        foreach ($properties as $property) {
            if ($property->getName() === $name) {
                $property->setValue($value);

                return $this;
            }
        }

        $newProperty = new RemoteEntityPropertyDraft();
        $newProperty->setName($name);
        $newProperty->setValue($value);
        $properties[] = $newProperty;
        $dto->setProperties($properties);

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function setName(string $value, $language = null)
    {
        $dto = $language ? $this->getTranslation($language) : $this->dto;
        $dto->setName(mb_strcut($value, 0, 1000));

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function setPublished(bool $value, $language = null)
    {
        $dto = $language ? $this->getTranslation($language) : $this->dto;
        $dto->setPublished($value);

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function setPreviewHtml(string $value, $language = null)
    {
        $dto = $language ? $this->getTranslation($language) : $this->dto;

        $file = $this->core->sendFile(FileType::ENTITY_PREVIEW, 'preview.html', $value);
        $dto->setPreviewHtmlFileId($file->getId());

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function setSourceDeepLink(string $value, $language = null)
    {
        $dto = $language ? $this->getTranslation($language) : $this->dto;
        $dto->setViewUrl($value);

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getProperty(string $name, $language = null)
    {
        $dto = $language ? $this->getTranslation($language) : $this->dto;

        $properties = $dto->getProperties();
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
    public function execute()
    {
        if ($this->is_deletion) {
            $entityType = $this->dto->getEntityTypeByMachineName();

            $dto = new DeleteRemoteEntityRevisionDto();
            $dto->setFlowMachineName($this->dto->getFlowMachineName());
            $dto->setEntityTypeNamespaceMachineName($entityType->getNamespaceMachineName());
            $dto->setEntityTypeMachineName($entityType->getMachineName());
            $dto->setLanguage($this->dto->getLanguage());
            $dto->setRemoteUuid($this->dto->getRemoteUuid());
            $dto->setRemoteUniqueId($this->dto->getRemoteUniqueId());
            $dto->setName($this->dto->getName());

            $request = $this
                ->core
                ->getClient()
                ->remoteEntityRevisionControllerDeleteRequest(deleteRemoteEntityRevisionDto: $dto)
            ;

            $this->core->sendToSyncCore($request, IApplicationInterface::SYNC_CORE_PERMISSIONS_CONTENT, false, SyncCore::PUSH_RETRY_COUNT);

            return $this;
        }
        $request = $this
            ->core
            ->getClient()
            ->remoteEntityRevisionControllerCreateRequest(createRemoteEntityRevisionDto: $this->dto)
        ;

        $this
            ->core
            ->sendToSyncCore($request, IApplicationInterface::SYNC_CORE_PERMISSIONS_CONTENT, false, SyncCore::PUSH_RETRY_COUNT)
        ;

        return $this;
    }

    /**
     * @return $this|IPushSingle|PushSingle
     */
    public function uploadFile(string $content, ?string $name = null, ?string $mimetype = null)
    {
        if (!$name) {
            $name = $this->dto->getName();
            if (!$name) {
                $name = 'entity file attachment';
            }
        }

        $file = $this->core->sendFile(FileType::ENTITY_FILE, $name, $content, true, false, $mimetype);
        $this->setProperty(DefineEntityType::FILE_PROPERTY_NAME, [
            'id' => $file->getId(),
        ]);

        return $this;
    }

    /**
     * @return string
     */
    public function serialize()
    {
        $translations = [];
        foreach ($this->translations as $translation) {
            $translations[] = [
                'dtoClass' => get_class($translation),
                'dtoSerialized' => $translation->jsonSerialize(),
            ];
        }

        return serialize([
            'syncCore' => $this->serializeSyncCore(),
            'dtoClass' => $this->dto ? get_class($this->dto) : null,
            'dtoClass' => $this->dto ? get_class($this->dto) : null,
            'dtoSerialized' => $this->dto ? $this->dto->jsonSerialize() : null,
            'translations' => $translations,
            'isDependency' => $this->is_dependency,
            'isDeletion' => $this->is_deletion,
        ]);
    }

    /**
     * @param string $serialized
     */
    public function unserialize($serialized)
    {
        $data = unserialize($serialized);

        $this->unserializeSyncCore($data['syncCore']);

        $this->is_dependency = $data['isDependency'];
        $this->is_deletion = $data['isDeletion'];

        $this->dto = @ObjectSerializer::deserialize($data['dtoSerialized'], $data['dtoClass'], []);

        $this->translations = [];
        foreach ($data['translations'] as $translation) {
            $this->translations[] = @ObjectSerializer::deserialize($translation['dtoSerialized'], $translation['dtoClass'], []);
        }
    }

    protected function initEmptyDto(string $language, ?string $flowMachineName = null, ?EntityTypeVersionReference $typeReference = null, ?string $uuid = null, ?string $unique_id = null)
    {
        $has_any_id = $uuid || $unique_id;
        if (!$uuid && !$has_any_id) {
            $uuid = $this->dto->getRemoteUuid();
        }
        if (!$unique_id && !$has_any_id) {
            $unique_id = $this->dto->getRemoteUniqueId();
        }

        $dto = new CreateRemoteEntityRevisionDto();
        $dto->setFlowMachineName($flowMachineName ?? $this->dto->getFlowMachineName());
        $dto->setRemoteUuid($uuid);
        $dto->setRemoteUniqueId($unique_id);
        $dto->setEntityTypeByMachineName($typeReference ?? $this->dto->getEntityTypeByMachineName());
        $dto->setPoolMachineNames($has_any_id ? [] : $this->dto->getPoolMachineNames());
        $dto->setProperties([]);
        $dto->setDirectDependencies([]);
        $dto->setLanguage($language);

        /**
         * @var SiteApplicationType $type
         */
        $type = $this->core->getApplication()->getApplicationId();
        $dto->setAppType($type);

        return $dto;
    }

    protected function getTranslation($language)
    {
        foreach ($this->translations as $translation) {
            if ($translation->getLanguage() === $language) {
                return $translation;
            }
        }

        $translation = $this->initEmptyDto($language);

        $this->translations[$language] = $translation;

        $this->dto->setTranslations(array_values($this->translations));

        return $translation;
    }
}
