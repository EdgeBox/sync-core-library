<?php

namespace EdgeBox\SyncCore\V1\Query;

use EdgeBox\SyncCore\V1\Query\Result\SimpleResult;

/**
 * Class SimpleQuery.
 *
 * A query to execute against the Sync Core, directly providing a path.
 */
class SimpleQuery extends Query
{
    /**
     * @var string
     */
    protected $path;

    /**
     * Query constructor.
     *
     * @param \EdgeBox\SyncCore\V1\SyncCore $core
     * @param string                        $path
     * @param array                         $arguments
     */
    public function __construct($core, $path, $arguments = [])
    {
        parent::__construct($core, $arguments);
        $this->path = $path;
    }

    /**
     * {@inheritdoc}
     */
    public static function create($client, $path, $arguments = [])
    {
        return new SimpleQuery($client, $path, $arguments);
    }

    /**
     * Get the path to be appended after the Pool::backend_url for this Query.
     *
     * @return string
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * Provide a Result object to get the actual entities from.
     *
     * @throws \EdgeBox\SyncCore\Exception\SyncCoreException
     *
     * @return \EdgeBox\SyncCore\V1\Query\Result\SimpleResult
     */
    public function execute()
    {
        $result = new SimpleResult($this);

        $result->execute();

        return $result;
    }
}
