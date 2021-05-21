<?php

namespace EdgeBox\SyncCore\V2\Syndication;

use EdgeBox\SyncCore\Interfaces\IApplicationInterface;
use EdgeBox\SyncCore\Interfaces\Syndication\IPushSingle;
use EdgeBox\SyncCore\V2\Configuration\DefineEntityType;
use EdgeBox\SyncCore\V2\Helper;
use EdgeBox\SyncCore\V2\Raw\Model\CreateRemoteEntityRevisionDto;
use EdgeBox\SyncCore\V2\Raw\Model\DeleteRemoteEntityRevisionDto;
use EdgeBox\SyncCore\V2\Raw\Model\EntityTypeVersionReference;
use EdgeBox\SyncCore\V2\Raw\Model\FileType;
use EdgeBox\SyncCore\V2\Raw\Model\RemoteEntityDependency;
use EdgeBox\SyncCore\V2\Raw\Model\RemoteEntityProperty;
use EdgeBox\SyncCore\V2\Raw\Model\RemoteEntityTypePropertyType;
use EdgeBox\SyncCore\V2\SyncCore;

class PushSingle implements IPushSingle
{
    public const RESERVED_PROPERTY_NAMES = [];

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
    public function __construct(SyncCore $core, string $namespaceMachineName, string $machineName, string $uuid, ?string $unique_id)
    {
        $this->core = $core;

        $typeReference = new EntityTypeVersionReference([
            'namespaceMachineName' => $namespaceMachineName,
            'machineName' => $machineName,
            // TODO: Interface: Require versionId to be provided to IPushSingle
            'versionId' => '',
        ]);

        $this->dto = new CreateRemoteEntityRevisionDto([
            'remoteUuid' => $uuid,
            'remoteUniqueId' => $unique_id,
            'entityTypeByMachineName' => $typeReference,
        ]);

        $this->translations = [];

        self::$serializedEntities[$namespaceMachineName][$uuid] = &$this->dto;
    }

    protected function getTranslation($language)
    {
        foreach ($this->translations as $translation) {
            if ($translation->getLanguage() === $language) {
                return $translation;
            }
        }

        $translation = new CreateRemoteEntityRevisionDto();
        $translation->setRemoteUuid($this->dto->getRemoteUuid());
        $translation->setRemoteUniqueId($this->dto->getRemoteUniqueId());
        $translation->setEntityTypeByMachineName($this->dto->getEntityTypeByMachineName());
        $translation->setPoolMachineNames($this->dto->getPoolMachineNames());

        $this->translations[$language] = $translation;

        $this->dto->setTranslations(array_values($this->translations));

        return $translation;
    }

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
        // TODO: Support multiple.
        $this->dto->setPoolMachineNames([$pool_id]);

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
     * @return string
     */
    public function getPoolId()
    {
        // TODO: Support multiple.
        return $this->dto->getPoolMachineNames()[0];
    }

    /**
     * Get the definition for a referenced entity that should be pushed /
     * embedded as well.
     *
     * @param string     $type
     *                            The entity type of the referenced entity
     * @param string     $bundle
     *                            The bundle of the referenced entity
     * @param string     $uuid
     *                            The UUID of the referenced entity
     * @param string     $id
     *                            The ID of the entity, if it should be kept across sites
     * @param string     $version
     *                            The version hash of the entity type definition
     * @param string     $pool_id
     *                            The pool ID to add the entity for. Can be different to the current pool
     *                            ID.
     * @param array|null $details
     *                            Additional details you would like to push
     *
     * @return array|object the definition to be pushed
     */
    // TODO: Interface: We allow multiple pools at once.
    public function getEntityReferenceDto(string $type, string $bundle, string $uuid, string $id, string $version, string $pool_id, $details = null)
    {
        $entityReference = new RemoteEntityDependency();

        // TODO: Interface: We need to add the label somewhere (see PullOperation).

        $entityReference->setRemoteUuid($uuid);
        $entityReference->setRemoteUniqueId($id);

        $entityReference->setEntityTypeNamespaceMachineName($type);
        $entityReference->setEntityTypeMachineName($bundle);
        $entityReference->setEntityTypeVersion($version);

        $entityReference->setPoolMachineNames([$pool_id]);

        if ($details) {
            /**
             * @var object $details
             */
            $entityReference->setReferenceDetails($details);
        }

        return $entityReference;
    }

    /**
     * Embed an entity by its properties.
     *
     * @param string $type
     *                        {@see SyncIntent::getEmbedEntityDefinition}
     * @param string $bundle
     *                        {@see SyncIntent::getEmbedEntityDefinition}
     * @param string $uuid
     *                        {@see SyncIntent::getEmbedEntityDefinition}
     * @param string $id
     *                        The ID of the entity, if it should be kept across sites
     * @param string $version
     *                        {@see SyncIntent::getEmbedEntityDefinition}
     * @param string $pool_id
     *                        {@see SyncIntent::getEmbedEntityDefinition}
     * @param null   $details
     *                        {@see SyncIntent::getEmbedEntityDefinition}
     *
     * @return array The definition you can store via {@see SyncIntent::setField}
     *
     * @see SyncIntent::getEmbedEntityDefinition
     * @see SyncIntent::embedEntity
     */
    public function addDirectDependency(string $type,
                                      string $bundle,
                                      string $uuid,
                                      string $id,
                                      bool $embed,
                                      string $version,
                                      string $pool_id,
                                      $details = null)
    {
        if ($embed) {
            $embeds = $this->dto->getEmbed();

            if (!$embeds) {
                $embeds = [];
            } else {
                // Already included? Just return the definition then.
                foreach ($embeds as $definition) {
                    if ($definition->getEntityTypeNamespaceMachineName() === $type && $definition->getRemoteUuid() == $uuid && $definition->getRemoteUniqueId() == $id) {
                        return $this->getEntityReferenceDto(
                $type, $bundle, $uuid, $id, $version, $pool_id, $details
            );
                    }
                }
            }

            $dto = $this->getEntityReferenceDto(
          $type, $bundle, $uuid, $id, $version, $pool_id, $details
      );

            $embeds[] = $dto;

            $this->dto->setEmbed($embeds);

            return $dto;
        }

        $direct_dependencies = $this->dto->getEmbed();

        if (!$direct_dependencies) {
            $direct_dependencies = [];
        } else {
            // Already included? Just return the definition then.
            foreach ($direct_dependencies as $definition) {
                if ($definition->getEntityTypeNamespaceMachineName() === $type && $definition->getEntityTypeMachineName() === $bundle && $definition->getRemoteUuid() == $uuid && $definition->getRemoteUniqueId() == $id) {
                    return $this->getEntityReferenceDto(
              $type, $bundle, $uuid, $id, $version, $pool_id, $details
          );
                }
            }
        }

        $dto = $this->getEntityReferenceDto(
        $type, $bundle, $uuid, $id, $version, $pool_id, $details
    );

        $direct_dependencies[] = $dto;

        $this->dto->setDirectDependencies($direct_dependencies);

        return $dto;
    }

    public const DEPENDENCY_TYPE_OPTIONAL = 0;
    public const DEPENDENCY_TYPE_REQUIRED = 1;
    public const DEPENDENCY_TYPE_EMBED = 2;

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
    public function embed(string $type, string $bundle, string $uuid, ?string $id, string $version, IPushSingle $embed_entity, $details = null)
    {
        // Add all dependencies from the child directly to us, otherwise they'll
        // be missing on the remote site.
        foreach ($embed_entity->getDto()->getDirectDependencies() as $dependency) {
            $this->addDirectDependency(
          $dependency->getEntityTypeNamespaceMachineName(),
          $dependency->getEntityTypeMachineName(),
          $dependency->getRemoteUuid(),
          $dependency->getRemoteUniqueId(),
          false,
          $dependency->getEntityTypeVersion(),
          // TODO: Interface/Drupal: Support multiple.
          $dependency->getPoolMachineNames()[0],
          $dependency->getReferenceDetails()
      );
        }

        $embeds = $this->dto->getEmbed();
        if (!$embeds) {
            $embeds = [];
        }

        // If this is nested, we already get the serialized entity from the child.
        $nested_embeds = $embed_entity->getDto()->getEmbed();
        if ($nested_embeds) {
            foreach ($nested_embeds as $embed) {
                $embeds[] = $embed;
            }
        }
        // Clear theirs as we have them with us now.
        $embed_entity->getDto()->setEmbed([]);

        $this->dto->setEmbed($embeds);

        return $this->addDirectDependency(
        $type,
        $bundle,
        $uuid,
        $id,
        true,
        $version,
        // TODO: Interface/Drupal: Support multiple.
        $embed_entity->getPoolId(),
        $details
    );
    }

    /**
     * {@inheritdoc}
     */
    public function addDependency(string $type, string $bundle, string $uuid, ?string $id, string $version, string $pool_id, $details = null)
    {
        return $this->addDirectDependency(
      $type,
      $bundle,
      $uuid,
      $id,
      false,
      $version,
      $pool_id,
      $details
    );
    }

    /**
     * {@inheritdoc}
     */
    public function addReference(string $type, string $bundle, string $uuid, ?string $id, string $version, string $pool_id, $details = null)
    {
        return $this->getEntityReferenceDto(
      $type,
      $bundle,
      $uuid,
      $id,
      $version,
      $pool_id,
      $details
    );
    }

    /**
     * {@inheritdoc}
     */
    public function setProperty(string $name, $value, $language = null)
    {
        $dto = $language ? $this->getTranslation($language) : $this->dto;

        $properties = $dto->getProperties();
        foreach ($properties as $property) {
            if ($property->getName() === $name) {
                $property->setValue($value);

                return $this;
            }
        }

        $newProperty = new RemoteEntityProperty([
            // TODO: Interface: Ask for type.
            'type' => RemoteEntityTypePropertyType::OBJECT,
            'name' => $name,
            'value' => $value,
        ]);
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
        $dto->setName($value);

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function setPreviewHtml(string $value, $language = null)
    {
        $dto = $language ? $this->getTranslation($language) : $this->dto;

        // TODO: Drupal: Don't bother generating "has no preview" preview HTML. Just leave it empty in that case.
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
            $dto->setEntityTypeNamespaceMachineName($entityType->getNamespaceMachineName());
            $dto->setEntityTypeMachineName($entityType->getMachineName());
            $dto->setLanguage($this->dto->getLanguage());
            $dto->setRemoteUuid($this->dto->getRemoteUuid());
            $dto->setRemoteUniqueId($this->dto->getRemoteUniqueId());

            $request = $this
          ->core
          ->getClient()
          ->remoteEntityRevisionControllerDeleteRequest($dto);

            $this->core->sendToSyncCore($request, IApplicationInterface::SYNC_CORE_PERMISSIONS_CONTENT);

            return $this;
        } else {
            $request = $this
          ->core
          ->getClient()
          ->remoteEntityRevisionControllerCreateRequest($this->dto);

            $this
        ->core
        ->sendToSyncCore($request, IApplicationInterface::SYNC_CORE_PERMISSIONS_CONTENT);
        }

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    // TODO: Interface/Drupal: Add $name to IPushSingle

    /**
     * @return $this|IPushSingle|PushSingle
     */
    public function uploadFile(string $content, ?string $name = null)
    {
        if (!$name) {
            $name = $this->dto->getName();
            if (!$name) {
                $name = 'entity file attachment';
            }
        }

        $file = $this->core->sendFile(FileType::ENTITY_FILE, $name, $content);
        $this->setProperty(DefineEntityType::FILE_PROPERTY_NAME, [
            'id' => $file->getId(),
        ]);

        return $this;
    }
}
