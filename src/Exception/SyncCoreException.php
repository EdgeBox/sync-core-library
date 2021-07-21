<?php

namespace EdgeBox\SyncCore\Exception;

use Exception;

/**
 * The Sync Core responded with a non-OK status code or the request failed for
 * other reasons (timeout for example).
 */
class SyncCoreException extends Exception
{
    /**
     * @var null|int
     */
    protected $statusCode;

    /**
     * @var null|string
     */
    protected $reasonPhrase;

    /**
     * @var null|string
     */
    protected $responseBody;

    /**
     * SyncCoreException constructor.
     *
     * @param string      $message
     *                                  Error message
     * @param null|int    $statusCode
     *                                  HTTP Status Code
     * @param null|string $reasonPhrase
     *                                  HTTP Reason Phrase
     * @param null|string $responseBody
     *                                  HTTP Body
     */
    public function __construct($message = '', $statusCode = null, $reasonPhrase = null, $responseBody = null)
    {
        parent::__construct($message);

        $this->statusCode = $statusCode;
        $this->reasonPhrase = $reasonPhrase;
        $this->responseBody = $responseBody;
    }

    /**
     * @return null|int
     */
    public function getStatusCode()
    {
        return $this->statusCode;
    }

    /**
     * @return null|string
     */
    public function getReasonPhrase()
    {
        return $this->reasonPhrase;
    }

    /**
     * @return null|string
     */
    public function getResponseBody()
    {
        return $this->responseBody;
    }
}
