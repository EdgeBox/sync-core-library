<?php

namespace EdgeBox\SyncCore\V1\Syndication;

use EdgeBox\SyncCore\Interfaces\Syndication\IPushSingle;
use EdgeBox\SyncCore\V1\Entity\Entity;
use EdgeBox\SyncCore\V1\Helper;
use EdgeBox\SyncCore\V1\Storage\CustomStorage;
use EdgeBox\SyncCore\V1\Storage\InstanceStorage;
use EdgeBox\SyncCore\V1\SyncCore;

class PushSingle implements IPushSingle
{
    public const RESERVED_PROPERTY_NAMES = [
        Entity::UUID_KEY,
        Entity::ID_KEY,
        'embed_entities',
        'apiu_translation',
    ];

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
     * @var string
     */
    protected $entity_uuid;

    /**
     * @var string
     */
    protected $pool;

    /**
     * @var bool
     */
    protected $is_dependency;

    /**
     * @var bool
     */
    protected $is_deletion;

    /**
     * @var array
     */
    protected $body;

    /**
     * PushSingle constructor.
     *
     * @param SyncCore    $core
     * @param string      $type
     * @param string      $bundle
     * @param string      $entity_uuid
     * @param null|string $entity_id
     */
    public function __construct($core, $type, $bundle, $entity_uuid, $entity_id)
    {
        $this->core = $core;
        $this->type = $type;
        $this->bundle = $bundle;
        $this->entity_uuid = $entity_uuid;
        $this->body = [
            'embed_entities' => [],
            'uuid' => $entity_uuid,
            'id' => $entity_id ? $entity_id : $entity_uuid,
            'apiu_translation' => null,
        ];

        self::$serializedEntities[$type][$entity_uuid] = &$this->body;
    }

    /**
     * {@inheritdoc}
     */
    public function getEntityHash()
    {
        return self::getReferencedEntityHash($this->type, $this->entity_uuid);
    }

    /**
     * @param string $type
     * @param string $uuid
     *
     * @return string
     */
    public static function getReferencedEntityHash($type, $uuid)
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
        $this->pool = $pool_id;

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
     *
     * @param PushSingle $embed_entity
     */
    public function embed(string $type, string $bundle, ?string $uuid, ?string $id, string $version, IPushSingle $embed_entity, $details = null)
    {
        $data = $this->getEmbedEntityDefinition($type, $bundle, $uuid, $id, Entity::ENTITY_REFERENCE_EMBED, $version, $embed_entity->getPoolId(), $details);

        // If this is nested, we already get the serialized entity from the child.
        if (!isset($data[Entity::ENTITY_EMBED_KEY])) {
            $data[Entity::ENTITY_EMBED_KEY] = $embed_entity->getData();
        }

        // Add all dependencies from the child directly to us, otherwise they'll
        // be missing on the remote site.
        if (!empty($data[Entity::ENTITY_EMBED_KEY]['embed_entities'])) {
            foreach ($data[Entity::ENTITY_EMBED_KEY]['embed_entities'] as $embed) {
                $this->embedEntityDefinition(
                    $embed[Entity::ENTITY_TYPE_KEY],
                    $embed[Entity::BUNDLE_KEY],
                    $embed[Entity::UUID_KEY],
                    $embed[Entity::ID_KEY],
                    $embed[Entity::AUTO_PUSH_KEY],
                    $embed[Entity::VERSION_KEY],
                    $embed[Entity::API_KEY],
                    $embed
                );
            }
        }

        $data[Entity::ENTITY_EMBED_KEY]['embed_entities'] = [];

        return $data;
    }

    /**
     * Get the definition for a referenced entity that should be pushed /
     * embedded as well.
     *
     * @param string $type
     *                          The entity type of the referenced entity
     * @param string $bundle
     *                          The bundle of the referenced entity
     * @param string $uuid
     *                          The UUID of the referenced entity
     * @param string $id
     *                          The ID of the entity, if it should be kept across sites
     * @param int    $auto_push
     *                          Whether the referenced entity should be pushed automatically to all
     *                          it's pools as well
     * @param string $version
     *                          The version hash of the entity type definition
     * @param string $pool_id
     *                          The pool ID to add the entity for. Can be different to the current pool
     *                          ID.
     * @param array  $details
     *                          Additional details you would like to push
     *
     * @return array the definition to be pushed
     */
    public function getEmbedEntityDefinition($type, $bundle, $uuid, $id, $auto_push, $version, $pool_id, $details = null)
    {
        return array_merge([
            Entity::ENTITY_HASH_KEY => self::getReferencedEntityHash($type, $uuid),
            Entity::API_KEY => $pool_id,
            Entity::ENTITY_TYPE_KEY => $type,
            Entity::UUID_KEY => $uuid,
            Entity::ID_KEY => $id,
            Entity::BUNDLE_KEY => $bundle,
            Entity::VERSION_KEY => $version,
            Entity::AUTO_PUSH_KEY => $auto_push,
            Entity::SOURCE_CONNECTION_ID_KEY => CustomStorage::getCustomId(
                $pool_id,
                $this->core->getApplication()->getSiteMachineName(),
                $type,
                $bundle
            ),
            Entity::POOL_CONNECTION_ID_KEY => CustomStorage::getCustomId(
                $pool_id,
                InstanceStorage::POOL_SITE_ID,
                $type,
                $bundle
            ),
        ], $details ? $details : []);
    }

    /**
     * @return string
     */
    public function getPoolId()
    {
        return $this->pool;
    }

    /**
     * {@inheritdoc}
     */
    public function getData()
    {
        return $this->body;
    }

    /**
     * Embed an entity by its properties.
     *
     * @param string $type
     *                          {@see SyncIntent::getEmbedEntityDefinition}
     * @param string $bundle
     *                          {@see SyncIntent::getEmbedEntityDefinition}
     * @param string $uuid
     *                          {@see SyncIntent::getEmbedEntityDefinition}
     * @param string $id
     *                          The ID of the entity, if it should be kept across sites
     * @param int    $auto_push
     *                          {@see SyncIntent::getEmbedEntityDefinition}
     * @param string $version
     *                          {@see SyncIntent::getEmbedEntityDefinition}
     * @param string $pool_id
     *                          {@see SyncIntent::getEmbedEntityDefinition}
     * @param array  $details
     *                          {@see SyncIntent::getEmbedEntityDefinition}
     *
     * @return array The definition you can store via {@see SyncIntent::setField}
     *
     * @see SyncIntent::getEmbedEntityDefinition
     * @see SyncIntent::embedEntity
     */
    public function embedEntityDefinition($type, $bundle, $uuid, $id, $auto_push, $version, $pool_id, $details = null)
    {
        // Already included? Just return the definition then.
        foreach ($this->body['embed_entities'] as &$definition) {
            if ($definition[Entity::ENTITY_TYPE_KEY] == $type && $definition[Entity::UUID_KEY] == $uuid && $definition[Entity::ID_KEY] == $id) {
                // Overwrite auto push flag if it should be set now.
                if (!$definition[Entity::AUTO_PUSH_KEY] && $auto_push) {
                    $definition[Entity::AUTO_PUSH_KEY] = $auto_push;
                }

                return $this->getEmbedEntityDefinition(
                    $type,
                    $bundle,
                    $uuid,
                    $id,
                    $auto_push,
                    $version,
                    $pool_id,
                    $details
                );
            }
        }

        $result = $this->getEmbedEntityDefinition(
            $type,
            $bundle,
            $uuid,
            $id,
            $auto_push,
            $version,
            $pool_id,
            $details
        );

        if ($auto_push) {
            $this->body['embed_entities'][] = $result;
        }

        return $result;
    }

    /**
     * {@inheritdoc}
     */
    public function addDependency(string $type, string $bundle, ?string $uuid, ?string $id, string $version, array $pool_machine_names, string $language, ?string $name, $details = null)
    {
        return $this->embedEntityDefinition(
            $type,
            $bundle,
            $uuid,
            $id,
            Entity::ENTITY_REFERENCE_PUSH_AS_DEPENDENCY,
            $version,
            // TODO: Drupal: Check whether pushing to multiple pools at once is possible.
      $pool_machine_names[0],
            $details
        );
    }

    /**
     * {@inheritdoc}
     */
    public function addReference(string $type, string $bundle, ?string $uuid, ?string $id, string $version, array $pool_machine_names, string $language, ?string $name, $details = null)
    {
        return $this->embedEntityDefinition(
            $type,
            $bundle,
            $uuid,
            $id,
            Entity::ENTITY_REFERENCE_RESOLVE_IF_EXISTS,
            $version,
      // TODO: Drupal: Check whether pushing to multiple pools at once is possible.
      $pool_machine_names[0],
            $details
        );
    }

    /**
     * {@inheritdoc}
     */
    public function setName(string $value, $language = null)
    {
        $this->setProperty(Entity::PROPERTY_NAME, $value, $language);

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function setProperty(string $name, $value, $language = null)
    {
        if ($language) {
            if (empty($this->body['apiu_translation'])) {
                $this->body['apiu_translation'] = [];
            }
            $this->body['apiu_translation'][$language][$name] = $value;
        } elseif (!in_array($name, self::RESERVED_PROPERTY_NAMES)) {
            $this->body[$name] = $value;
        }

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function setPreviewHtml(string $value, $language = null)
    {
        $this->setProperty(Entity::PROPERTY_PREVIEW_HTML, $value, $language);

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function setSourceDeepLink(string $value, $language = null)
    {
        $this->setProperty(Entity::PROPERTY_SOURCE_DEEP_LINK_URL, $value, $language);

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getProperty(string $name, $language = null)
    {
        if ($language) {
            if (!isset($this->body['apiu_translation'][$language][$name])) {
                return null;
            }

            return $this->body['apiu_translation'][$language][$name];
        }

        if (!isset($this->body[$name])) {
            return null;
        }

        return $this->body[$name];
    }

    /**
     * {@inheritdoc}
     */
    public function execute()
    {
        $storage = $this
            ->core
            ->storage
            ->getCustomStorage(
                $this->pool,
                $this->core->getApplication()->getSiteMachineName(),
                $this->type,
                $this->bundle
            )
        ;

        if ($this->is_deletion) {
            $query = $storage->deleteItem($this->body['id']);
        } else {
            $query = $storage->createItem($this->body);
        }

        $query
            ->setAsDependency($this->is_dependency)
            ->execute()
        ;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function uploadFile(string $content, ?string $name = null)
    {
        $this->setProperty('apiu_file_content', base64_encode($content));

        return $this;
    }
}
