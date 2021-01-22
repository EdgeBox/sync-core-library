<?php

namespace EdgeBox\SyncCore\Exception;

/**
 * The Sync Core responded with a non-OK status code or the request failed for
 * other reasons (timeout for example).
 */
class SyncCoreException extends \Exception {

  /**
   * @var int
   */
  protected $statusCode;

  /**
   * @var string
   */
  protected $reasonPhrase;

  /**
   * @var string
   */
  protected $responseBody;

  /**
   * SyncCoreException constructor.
   *
   * @param string $message
   *   Error message.
   * @param null $statusCode
   *   HTTP Status Code.
   * @param null $reasonPhrase
   *   HTTP Reason Phrase.
   * @param null $responseBody
   *   HTTP Body.
   */
  public function __construct($message = "", $statusCode = NULL, $reasonPhrase = NULL, $responseBody = NULL) {
    parent::__construct($message);

    $this->statusCode = $statusCode;
    $this->reasonPhrase = $reasonPhrase;
    $this->responseBody = $responseBody;
  }

  /**
   * @return int|null
   */
  public function getStatusCode() {
    return $this->statusCode;
  }

  /**
   * @return string|null
   */
  public function getReasonPhrase() {
    return $this->reasonPhrase;
  }

  /**
   * @return string|null
   */
  public function getResponseBody() {
    return $this->responseBody;
  }

}
