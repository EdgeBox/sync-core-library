<?php

namespace EdgeBox\SyncCore\V1\Query\Result;

/**
 * Class SimpleResult.
 *
 * Simply provide the response body as a result.
 */
class SimpleResult extends Result
{
    /**
     * @var array
     */
    protected $result;

    /**
     * Execute the query and store the result.
     *
     * @throws \EdgeBox\SyncCore\Exception\TimeoutException
     * @throws \EdgeBox\SyncCore\Exception\BadRequestException
     * @throws \EdgeBox\SyncCore\Exception\ForbiddenException
     * @throws \EdgeBox\SyncCore\Exception\NotFoundException
     * @throws \EdgeBox\SyncCore\Exception\SyncCoreException
     *
     * @return $this
     */
    public function execute()
    {
        $this->result = $this->query
            ->getCore()
            ->getClient()
            ->request($this->query);

        return $this;
    }

    /**
     * @return bool the result
     */
    public function succeeded()
    {
        return (bool) $this->result;
    }

    /**
     * @return array the entity data
     */
    public function getItem()
    {
        return $this->result;
    }

    /**
     * @return array
     */
    public function getResult()
    {
        return $this->result;
    }
}
