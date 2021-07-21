<?php

namespace EdgeBox\SyncCore\V1;

use EdgeBox\SyncCore\V1\Storage\ApiStorage;
use EdgeBox\SyncCore\V1\Storage\ConnectionStorage;
use EdgeBox\SyncCore\V1\Storage\ConnectionSynchronizationStorage;
use EdgeBox\SyncCore\V1\Storage\CustomStorage;
use EdgeBox\SyncCore\V1\Storage\EntityTypeStorage;
use EdgeBox\SyncCore\V1\Storage\InstanceStorage;
use EdgeBox\SyncCore\V1\Storage\MetaInformationConnectionStorage;
use EdgeBox\SyncCore\V1\Storage\ObjectStorage;
use EdgeBox\SyncCore\V1\Storage\PreviewEntityStorage;
use EdgeBox\SyncCore\V1\Storage\RemoteStorageStorage;

/**
 * Class Storages.
 *
 * Wrapper class providing all of the different storages from the Sync Core.
 */
class Storage
{
    /**
     * @var \EdgeBox\SyncCore\V1\SyncCore
     */
    protected $core;

    /**
     * Storages constructor.
     *
     * @param \EdgeBox\SyncCore\V1\SyncCore $core
     */
    public function __construct($core)
    {
        $this->core = $core;
    }

    /**
     * @param string $type
     *
     * @throws \Exception
     *
     * @return \EdgeBox\SyncCore\V1\Storage\Storage
     */
    public function getStorageById($type)
    {
        if (MetaInformationConnectionStorage::ID == $type) {
            return $this->getMetaInformationConnectionStorage();
        }
        if (ConnectionStorage::ID == $type) {
            return $this->getConnectionStorage();
        }
        if (EntityTypeStorage::ID == $type) {
            return $this->getEntityTypeStorage();
        }
        if (ConnectionSynchronizationStorage::ID == $type) {
            return $this->getConnectionSynchronizationStorage();
        }
        if (PreviewEntityStorage::ID == $type) {
            return $this->getPreviewEntityStorage();
        }
        if (InstanceStorage::ID == $type) {
            return $this->getInstanceStorage();
        }
        if (ApiStorage::ID == $type) {
            return $this->getApiStorage();
        }
        if (ObjectStorage::ID == $type) {
            return $this->getObjectStorage();
        }
        if (RemoteStorageStorage::ID == $type) {
            return $this->getRemoteStorage();
        }

        throw new \Exception("Unknown storage type {$type}.");
    }

    /**
     * @return \EdgeBox\SyncCore\V1\Storage\MetaInformationConnectionStorage
     */
    public function getMetaInformationConnectionStorage()
    {
        static $cache;
        if (!empty($cache)) {
            return $cache;
        }

        return $cache = new MetaInformationConnectionStorage($this->core);
    }

    /**
     * @return \EdgeBox\SyncCore\V1\Storage\ConnectionStorage
     */
    public function getConnectionStorage()
    {
        static $cache;
        if (!empty($cache)) {
            return $cache;
        }

        return $cache = new ConnectionStorage($this->core);
    }

    /**
     * @return \EdgeBox\SyncCore\V1\Storage\EntityTypeStorage
     */
    public function getEntityTypeStorage()
    {
        static $cache;
        if (!empty($cache)) {
            return $cache;
        }

        return $cache = new EntityTypeStorage($this->core);
    }

    /**
     * @return \EdgeBox\SyncCore\V1\Storage\ConnectionSynchronizationStorage
     */
    public function getConnectionSynchronizationStorage()
    {
        static $cache;
        if (!empty($cache)) {
            return $cache;
        }

        return $cache = new ConnectionSynchronizationStorage($this->core);
    }

    /**
     * @return \EdgeBox\SyncCore\V1\Storage\PreviewEntityStorage
     */
    public function getPreviewEntityStorage()
    {
        static $cache;
        if (!empty($cache)) {
            return $cache;
        }

        return $cache = new PreviewEntityStorage($this->core);
    }

    /**
     * @return \EdgeBox\SyncCore\V1\Storage\InstanceStorage
     */
    public function getInstanceStorage()
    {
        static $cache;
        if (!empty($cache)) {
            return $cache;
        }

        return $cache = new InstanceStorage($this->core);
    }

    /**
     * @return \EdgeBox\SyncCore\V1\Storage\ApiStorage
     */
    public function getApiStorage()
    {
        static $cache;
        if (!empty($cache)) {
            return $cache;
        }

        return $cache = new ApiStorage($this->core);
    }

    /**
     * @return \EdgeBox\SyncCore\V1\Storage\ObjectStorage
     */
    public function getObjectStorage()
    {
        static $cache;
        if (!empty($cache)) {
            return $cache;
        }

        return $cache = new ObjectStorage($this->core);
    }

    /**
     * @return \EdgeBox\SyncCore\V1\Storage\RemoteStorageStorage
     */
    public function getRemoteStorage()
    {
        static $cache;
        if (!empty($cache)) {
            return $cache;
        }

        return $cache = new RemoteStorageStorage($this->core);
    }

    /**
     * @param string $api_id
     * @param string $site_id
     * @param string $entity_type_name
     * @param string $bundle_name
     *
     * @return \EdgeBox\SyncCore\V1\Storage\CustomStorage
     */
    public function getCustomStorage($api_id, $site_id, $entity_type_name, $bundle_name)
    {
        static $cache;

        if (!empty($cache[$api_id][$site_id][$entity_type_name][$bundle_name])) {
            return $cache[$api_id][$site_id][$entity_type_name][$bundle_name];
        }

        return $cache[$api_id][$site_id][$entity_type_name][$bundle_name] = new CustomStorage(
            $this->core,
            $api_id,
            $site_id,
            $entity_type_name,
            $bundle_name
        );
    }
}
