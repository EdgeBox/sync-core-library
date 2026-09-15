<?php

declare(strict_types=1);

namespace EdgeBox\SyncCore\Tests;

use EdgeBox\SyncCore\Tests\Support\TestApplication;
use EdgeBox\SyncCore\V2\SyncCore;
use Firebase\JWT\JWT;
use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Middleware;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;

/**
 * Shared harness for the fixture tests: a SyncCore wired to a Guzzle MockHandler
 * so a wrapper's request can be captured and a canned response returned.
 *
 * @internal
 */
abstract class SyncCoreTestCase extends TestCase
{
    /**
     * @var MockHandler
     */
    protected $mock;

    /**
     * @var array<int, array{request: \Psr\Http\Message\RequestInterface}>
     */
    protected $history = [];

    /**
     * @var TestApplication
     */
    protected $application;

    /**
     * @param Response[] $responses
     */
    protected function syncCoreWithResponses(array $responses, ?TestApplication $application = null): SyncCore
    {
        $this->application = $application ?? new TestApplication();
        $this->mock = new MockHandler($responses);
        $stack = HandlerStack::create($this->mock);
        $this->history = [];
        $stack->push(Middleware::history($this->history));
        $this->application->httpClient = new Client(['handler' => $stack]);

        return new SyncCore($this->application, 'https://core.example.com/sync-core');
    }

    /**
     * The request the wrapper sent at the given position in the history.
     */
    protected function sentRequest(int $index = 0): \Psr\Http\Message\RequestInterface
    {
        return $this->history[$index]['request'];
    }

    /**
     * The decoded body of the request the wrapper sent.
     *
     * @return array
     */
    protected function sentBody(int $index = 0): array
    {
        return json_decode((string) $this->sentRequest($index)->getBody(), true) ?? [];
    }

    /**
     * Decode a bearer token off a captured request against the test secret.
     *
     * @return array
     */
    protected function decodeBearer(\Psr\Http\Message\RequestInterface $request): array
    {
        $header = $request->getHeaderLine('Authorization');
        $jwt = preg_replace('/^Bearer /', '', $header);
        $decoded = JWT::decode($jwt, $this->application->secret, ['HS256']);

        return json_decode(json_encode($decoded), true);
    }
}
