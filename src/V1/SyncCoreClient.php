<?php

namespace EdgeBox\SyncCore\V1;

use Drupal\cms_content_sync\SyncCore\Exception\BadRequestException;
use Drupal\cms_content_sync\SyncCore\Exception\ForbiddenException;
use Drupal\cms_content_sync\SyncCore\Exception\NotFoundException;
use Drupal\cms_content_sync\SyncCore\Exception\SyncCoreException;
use Drupal\cms_content_sync\SyncCore\Exception\TimeoutException;
use GuzzleHttp\Exception\ConnectException;
use GuzzleHttp\Exception\GuzzleException;

/**
 *
 */
class SyncCoreClient {

  /**
   * @var int MAX_ITEMS_PER_PAGE
   *    The maximum number of items that can be queried per page as restricted by the Sync Core.
   */
  const MAX_ITEMS_PER_PAGE = 100;

  const STATUS_PATH = '/status';

  /**
   * @var string METHOD_POST Perform an HTTP POST request.
   */
  const METHOD_POST = 'POST';

  const LOG_PATH = '/log';

  /**
   * @var string METHOD_PUT Perform an HTTP PUT request.
   */
  const METHOD_PUT = 'PUT';

  /**
   * @var string METHOD_PATCH Perform an HTTP PATCH request.
   */
  const METHOD_PATCH = 'PATCH';

  /**
   * @var string METHOD_GET Perform an HTTP GET request.
   */
  const METHOD_GET = 'GET';

  /**
   * @var string METHOD_DELETE Perform an HTTP DELETE request.
   */
  const METHOD_DELETE = 'DELETE';

  /**
   * @var \Drupal\cms_content_sync\SyncCore\V1\SyncCore
   */
  protected $syncCore;

  /**
   * @var \GuzzleHttp\Client
   *    The HTTP client used to execute requests.
   */
  protected $client;

  /**
   * SyncCoreClient constructor.
   *
   * @param \Drupal\cms_content_sync\SyncCore\V1\SyncCore $sync_core
   */
  public function __construct($sync_core) {
    $this->syncCore = $sync_core;
    $this->client = $this->syncCore->getApplication()->getHttpClient();
  }

  /**
   * Execute a GET request.
   *
   * @param \Drupal\cms_content_sync\SyncCore\V1\Query\Query $query
   *
   * @return mixed
   *
   * @throws \Drupal\cms_content_sync\SyncCore\Exception\TimeoutException
   * @throws \Drupal\cms_content_sync\SyncCore\Exception\BadRequestException
   * @throws \Drupal\cms_content_sync\SyncCore\Exception\ForbiddenException
   * @throws \Drupal\cms_content_sync\SyncCore\Exception\NotFoundException
   * @throws \Drupal\cms_content_sync\SyncCore\Exception\SyncCoreException
   */
  public function request($query) {
    $params = http_build_query($query->toArray());
    $url = $this->syncCore->getBaseUrl() . $query->getPath() . ($params ? '?' . $params : '');

    $method = $query->getMethod();
    $body = $query->getBody();

    try {
      $options = [
        'http_errors' => FALSE,
      ] + $this->syncCore->getApplication()->getHttpOptions();

      if ($body !== NULL) {
        $options['headers']['Content-Type'] = 'application/json';
        $options['body'] = json_encode($body);
      }

      if ($method === SyncCoreClient::METHOD_DELETE && empty($options['timeout'])) {
        $options['timeout'] = 15;
      }

      $response = $this->client->request(
        $method,
        $url,
        $options
      );
    }
    catch (ConnectException $e) {
      throw new TimeoutException('The Sync Core did not respond in time for ' . $method . ' ' . Helper::obfuscateCredentials($url));
    }
    catch (GuzzleException $e) {
      throw new SyncCoreException($e->getMessage());
    }
    catch (\Exception $e) {
      throw new SyncCoreException($e->getMessage());
    }

    $status = $response->getStatusCode();

    $returnBoolean = $query->returnBoolean();
    if ($returnBoolean && $status !== 200 && $status !== 201) {
      return FALSE;
    }

    $response_body = $response->getBody();
    $data = json_decode($response_body, TRUE);
    $message = isset($data['message']) ? $data['message'] : $response_body . '';

    if ($status === 400) {
      throw new BadRequestException('The Sync Core responded with 400 Bad Request for ' . $method . ' ' . Helper::obfuscateCredentials($url) . ' ' . $message, $status, $response->getReasonPhrase(), $response_body);
    }
    elseif ($status === 403) {
      throw new ForbiddenException('The Sync Core responded with 403 Forbidden for ' . $method . ' ' . Helper::obfuscateCredentials($url) . ' ' . $message, $status, $response->getReasonPhrase(), $response_body);
    }
    elseif ($status === 404) {
      throw new NotFoundException('The Sync Core responded with 404 Not Found for ' . $method . ' ' . Helper::obfuscateCredentials($url) . ' ' . $message, $status, $response->getReasonPhrase(), $response_body);
    }
    elseif ($status !== 200 && $status !== 201) {
      throw new SyncCoreException('The Sync Core responded with a non-OK status code for ' . $method . ' ' . Helper::obfuscateCredentials($url) . ' ' . $message, $status, $response->getReasonPhrase(), $response_body);
    }

    if ($returnBoolean) {
      if (is_string($returnBoolean)) {
        return $data[$returnBoolean];
      }

      return TRUE;
    }

    return $data;
  }

  /**
   * Receive an absolute URL to the Sync Core and provide a relative URL in return.
   * If you have a '_resource_url' somewhere, use this function to convert it to a relative URL you can then pass to
   * a simple query for example.
   *
   * @param string $url
   *
   * @return string
   */
  public static function getRelativeUrl($url) {
    if (substr($url, 0, 7) == 'http://' || substr($url, 0, 8) == 'https://') {
      // Remove protocol, host and path prefix so the path is relative and can be inserted into the normal Query classes.
      return preg_replace('@^https?://[^/]+/rest@', '', $url);
    }

    // Is already relative.
    return $url;
  }

}
