<?php

namespace EdgeBox\SyncCore\V1\Query\Result;

/**
 * Class Result
 * A helper class to get the results of a Query object. Will use the Client
 * class to execute the requests and provide them with individual helper
 * functions in the subclasses.
 */
class Result
{
    /**
     * @var \EdgeBox\SyncCore\V1\Query\Query
     */
    protected $query;

    /**
     * Result constructor.
     *
     * @param \EdgeBox\SyncCore\V1\Query\Query $query
     */
    public function __construct($query)
    {
        $this->query = $query;
    }
}
