<?php

namespace EdgeBox\SyncCore\V1\Storage;

/**
 * Class EntityTypeStorage
 * Implement Storage for the Sync Core "Entity Type" entity type.
 */
class EntityTypeStorage extends Storage
{
    public const ID = 'api_unify-api_unify-entity_type-0_1';

    /**
     * Get the Sync Core entity type ID for the given entity type config.
     *
     * @param string      $api_id
     *                                      API ID from this config
     * @param string      $entity_type_name
     *                                      The entity type
     * @param string      $bundle_name
     *                                      The bundle
     * @param null|string $version
     *                                      The version. {@see Flow::getEntityTypeVersion}.
     *
     * @return string a unique entity type ID
     */
    public static function getExternalEntityTypeId($api_id, $entity_type_name, $bundle_name, $version = null)
    {
        return sprintf(
            'drupal-%s-%s-%s%s',
            $api_id,
            $entity_type_name,
            $bundle_name,
            null !== $version ? '-'.$version : ''
        );
    }

    /**
     * {@inheritdoc}
     */
    public function getId()
    {
        return self::ID;
    }
}
