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
     * @var int|null
     */
    protected $statusCode;

    /**
     * @var string|null
     */
    protected $reasonPhrase;

    /**
     * @var string|null
     */
    protected $responseBody;

    /**
     * SyncCoreException constructor.
     *
     * @param string      $message
     *                                  Error message
     * @param int|null    $statusCode
     *                                  HTTP Status Code
     * @param string|null $reasonPhrase
     *                                  HTTP Reason Phrase
     * @param string|null $responseBody
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
     * @return int|null
     */
    public function getStatusCode()
    {
        return $this->statusCode;
    }

    /**
     * @return string|null
     */
    public function getReasonPhrase()
    {
        return $this->reasonPhrase;
    }

    /**
     * @return string|null
     */
    public function getResponseBody()
    {
        return $this->responseBody;
    }
}
