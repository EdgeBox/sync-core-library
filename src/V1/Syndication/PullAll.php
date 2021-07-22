<?php

namespace EdgeBox\SyncCore\V1\Syndication;

use EdgeBox\SyncCore\Interfaces\Syndication\IPullAll;
use EdgeBox\SyncCore\V1\SerializableWithSyncCoreReference;
use EdgeBox\SyncCore\V1\Storage\ConnectionSynchronizationStorage;
use EdgeBox\SyncCore\V1\Storage\CustomStorage;

class PullAll extends SerializableWithSyncCoreReference implements IPullAll
{
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
    protected $pool;

    /**
     * @var bool
     */
    protected $is_force;

    /**
     * @var null|int
     */
    protected $total;

    /**
     * @var int
     */
    protected $progress = 0;

    /**
     * @var null|int
     */
    protected $sync_id;

    /**
     * @param mixed $core
     * @param mixed $type
     * @param mixed $bundle
     */
    public function __construct($core, $type, $bundle)
    {
        parent::__construct($core);

        $this->type = $type;
        $this->bundle = $bundle;
    }

    /**
     * {@inheritdoc}
     */
    public function fromPool($pool_id)
    {
        $this->pool = $pool_id;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function force($set)
    {
        $this->is_force = $set;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function total()
    {
        return $this->total;
    }

    /**
     * {@inheritdoc}
     */
    public function progress($fromCache = false)
    {
        if (!$this->sync_id && 0 !== $this->sync_id) {
            throw new \Exception("Can't get syndication progress before executing the pull all operation.");
        }

        if ($this->progress === $this->total || $fromCache) {
            return $this->progress;
        }

        $synchronization = $this->getSyncEntity();

        $body = $synchronization
            ->synchronizeAllStatus($this->sync_id)
            ->execute()
            ->getResult()
        ;

        return $this->progress = (int) $body['processed'];
    }

    /**
     * {@inheritdoc}
     */
    public function getPoolMachineName()
    {
        return $this->pool;
    }

    /**
     * {@inheritdoc}
     */
    public function getTypeMachineName()
    {
        return $this->type;
    }

    /**
     * {@inheritdoc}
     */
    public function getBundleMachineName()
    {
        return $this->bundle;
    }

    /**
     * {@inheritdoc}
     */
    public function execute()
    {
        $response = $this
            ->getSyncEntity()
            ->synchronizeAll()
            ->force($this->is_force)
            ->execute()
            ->getResult()
        ;

        $this->total = (int) $response['total'];
        $this->sync_id = isset($response['id']) ? $response['id'] : null;

        return $this;
    }

    /**
     * @return string
     */
    public function serialize()
    {
        return serialize([
            $this->serializeSyncCore(),
            $this->type,
            $this->bundle,
            $this->pool,
            $this->total,
            $this->progress,
            $this->sync_id,
        ]);
    }

    /**
     * @param string $serialized
     */
    public function unserialize($serialized)
    {
        $data = unserialize($serialized);

        $this->unserializeSyncCore($data[0]);

        $this->type = $data[1];
        $this->bundle = $data[2];
        $this->pool = $data[3];
        $this->total = $data[4];
        $this->progress = $data[5];
        $this->sync_id = $data[6];
    }

    /**
     * @return \EdgeBox\SyncCore\V1\Entity\ConnectionSynchronization
     */
    protected function getSyncEntity()
    {
        $api = $this->pool;
        $site_id = $this->core->getApplication()->getSiteMachineName();

        $local_connection_id = CustomStorage::getCustomId($api, $site_id, $this->type, $this->bundle);
        $sync_id = ConnectionSynchronizationStorage::getExternalConnectionSynchronizationId($local_connection_id, false);

        return $this->core
            ->storage->getConnectionSynchronizationStorage()
            ->getEntity($sync_id)
        ;
    }
}
