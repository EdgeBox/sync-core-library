<?php

namespace EdgeBox\SyncCore\V1\Query;

use EdgeBox\SyncCore\V1\Query\Result\SimpleResult;
use EdgeBox\SyncCore\V1\SyncCoreClient;

/**
 * Class PingQuery.
 *
 * Ask the Sync Core to ping this site to make sure it's accessible.
 */
class PingQuery extends SimpleQuery
{
    public const PATH = '/ping';

    /**
     * @var array
     */
    protected $body;

    /**
     * Query constructor.
     *
     * @param \EdgeBox\SyncCore\V1\SyncCore $core
     */
    public function __construct($core)
    {
        parent::__construct($core, self::PATH);
    }

    /**
     * {@inheritdoc}
     */
    public static function create($client, $path, $arguments = [])
    {
        return new PingQuery($client);
    }

    /**
     * {@inheritdoc}
     */
    public function getMethod()
    {
        return SyncCoreClient::METHOD_POST;
    }

    /**
     * {@inheritdoc}
     */
    public function getBody()
    {
        return $this->body;
    }

    /**
     * @param string $set
     *
     * @return $this
     */
    public function setSiteUrl($set)
    {
        $this->body['url'] = $set;

        return $this;
    }

    /**
     * @param string $set
     *
     * @return $this
     */
    public function setMethod($set)
    {
        $this->body['method'] = $set;

        return $this;
    }

    /**
     * @param string $set
     *
     * @return $this
     */
    public function setAuthentication($set)
    {
        $this->body['authentication'] = $set;

        return $this;
    }

    /**
     * Provide a Result object to get the actual entities from.
     *
     * @return \EdgeBox\SyncCore\V1\Query\Result\SimpleResult
     *
     * @throws \EdgeBox\SyncCore\Exception\SyncCoreException
     */
    public function execute()
    {
        $result = new SimpleResult($this);

        $result->execute();

        return $result;
    }
}
