<?php

namespace EdgeBox\SyncCore\V1\Query;

/**
 * Class StorageQuery.
 *
 * A query to execute against a specific storage of the Sync Core. Will return
 * a Result object when executed. This is just a simple helper class to
 * simplify query creation in an OOP fashion.
 */
abstract class StorageQuery extends Query
{
    /**
     * @var \EdgeBox\SyncCore\V1\Storage\Storage
     */
    protected $storage;

    /**
     * Query constructor.
     *
     * @param \EdgeBox\SyncCore\V1\Storage\Storage $storage
     */
    public function __construct($storage)
    {
        parent::__construct($storage->getClient());

        $this->storage = $storage;
    }

    /**
     * Get a RequestArguments instance.
     *
     * @param \EdgeBox\SyncCore\V1\Storage\Storage $storage
     *
     * @return \EdgeBox\SyncCore\V1\Query\Query
     */
    abstract public static function create($storage);

    /**
     * Get the Storage the Query belongs to.
     *
     * @return \EdgeBox\SyncCore\V1\Storage\Storage
     */
    public function getStorage()
    {
        return $this->storage;
    }
}
