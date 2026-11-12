<?php

declare(strict_types=1);

namespace EdgeBox\SyncCore\Tests;

use EdgeBox\SyncCore\Exception\ConnectionFailedException;
use EdgeBox\SyncCore\Exception\SyncCoreException;
use EdgeBox\SyncCore\Exception\TimeoutException;
use EdgeBox\SyncCore\V2\SyncCore;
use EdgeBox\SyncCore\V2\Syndication\SyndicationService;
use GuzzleHttp\Exception\ConnectException;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\Attributes\DataProvider;

/**
 * A request that never left is told apart from one that may have reached the
 * Sync Core: the first is a ConnectionFailedException, the second a plain
 * TimeoutException, and both are caught as a TimeoutException.
 *
 * The connection and transfer failures carry the handler context Guzzle's
 * cURL handler gives them: the cURL error number and the request bytes sent.
 * Guzzle reports a failure while connecting as a ConnectException, and one
 * once the transfer is under way as a RequestException.
 *
 * @internal
 */
final class ConnectionFailedExceptionTest extends SyncCoreTestCase
{
    private const URL = 'https://core.example.com/sync-core/migration';

    /**
     * @return array<string, array{int}>
     */
    public static function errorsBeforeSending(): array
    {
        return [
            'a host name that does not resolve' => [6],
            'a refused connection' => [7],
            'a timeout before anything was sent' => [28],
            'a failed TLS handshake' => [35],
        ];
    }

    #[DataProvider('errorsBeforeSending')]
    public function testARequestThatNeverLeftIsAConnectionFailure(int $errno): void
    {
        $core = $this->syncCoreWithResponses([$this->connectionFailure($errno, 0)]);

        $exception = $this->failure($core, 1);

        $this->assertInstanceOf(ConnectionFailedException::class, $exception);
        $this->assertInstanceOf(TimeoutException::class, $exception);
        $this->assertCount(1, $this->history);
    }

    /**
     * @return array<string, array{int}>
     */
    public static function errorsAfterSending(): array
    {
        return [
            'a timeout while waiting for the answer' => [28],
            'a connection closed without an answer' => [52],
        ];
    }

    #[DataProvider('errorsAfterSending')]
    public function testARequestThatWasSentStaysATimeout(int $errno): void
    {
        $core = $this->syncCoreWithResponses([$this->connectionFailure($errno, 312)]);

        $exception = $this->failure($core, 1);

        $this->assertNotInstanceOf(ConnectionFailedException::class, $exception);
    }

    public function testAConnectionFailureWithoutTheCurlDetailsStaysATimeout(): void
    {
        $core = $this->syncCoreWithResponses([
            new ConnectException('Connection refused', new Request('POST', self::URL)),
        ]);

        $exception = $this->failure($core, 1);

        $this->assertNotInstanceOf(ConnectionFailedException::class, $exception);
    }

    public function testEveryAttemptFailingToConnectIsAConnectionFailure(): void
    {
        $core = $this->syncCoreWithResponses([
            $this->connectionFailure(7, 0),
            $this->connectionFailure(6, 0),
            $this->connectionFailure(7, 0),
        ]);

        $exception = $this->failure($core, 3);

        $this->assertInstanceOf(ConnectionFailedException::class, $exception);
        $this->assertCount(3, $this->history);
    }

    public function testAFailedConnectionAfterASentAttemptStaysATimeout(): void
    {
        $core = $this->syncCoreWithResponses([
            $this->connectionFailure(28, 312),
            $this->connectionFailure(7, 0),
        ]);

        $exception = $this->failure($core, 2);

        $this->assertNotInstanceOf(ConnectionFailedException::class, $exception);
        $this->assertCount(2, $this->history);
    }

    public function testAFailedConnectionAfterAnAnsweredAttemptStaysATimeout(): void
    {
        $core = $this->syncCoreWithResponses([
            new Response(502, [], '{"message":"bad gateway"}'),
            $this->connectionFailure(7, 0),
        ]);

        $exception = $this->failure($core, 2);

        $this->assertNotInstanceOf(ConnectionFailedException::class, $exception);
        $this->assertCount(2, $this->history);
    }

    public function testAMassPushThatNeverLeftIsAConnectionFailure(): void
    {
        $failures = array_fill(0, SyncCore::PUSH_RETRY_COUNT, $this->connectionFailure(7, 0));
        $push = (new SyndicationService($this->syncCoreWithResponses($failures)))
            ->massPush()
            ->withFlow('articles')
            ->withNamespaceMachineName('node')
            ->withEntityTypeMachineName('article')
            ->withEntityTypeVersion('1111111111111111')
        ;

        try {
            $push->execute();
            $this->fail('The connection failure was not reported.');
        } catch (ConnectionFailedException $e) {
            $this->assertCount(SyncCore::PUSH_RETRY_COUNT, $this->history);
            $this->assertNull($push->getMigrationId());
        }
    }

    /**
     * @return array<string, array{int}>
     */
    public static function transferErrorsAfterSending(): array
    {
        return [
            'a failure while sending the request' => [55],
            'a failure while receiving the answer' => [56],
            'a failed HTTP/2 stream' => [92],
        ];
    }

    #[DataProvider('transferErrorsAfterSending')]
    public function testATransferThatFailedAfterSendingIsATimeout(int $errno): void
    {
        $core = $this->syncCoreWithResponses([$this->transferFailure($errno, 312)]);

        $exception = $this->failure($core, 3);

        $this->assertNotInstanceOf(ConnectionFailedException::class, $exception);
        $this->assertStringContainsString('cURL error '.$errno, $exception->getMessage());
        $this->assertCount(1, $this->history);
    }

    public function testATransferFailureWithoutTheCurlDetailsIsATimeout(): void
    {
        $core = $this->syncCoreWithResponses([
            new RequestException('Connection reset by peer', new Request('POST', self::URL)),
        ]);

        $exception = $this->failure($core, 1);

        $this->assertNotInstanceOf(ConnectionFailedException::class, $exception);
    }

    /**
     * @return array<string, array{int}>
     */
    public static function transferErrorsBeforeSending(): array
    {
        return [
            'a certificate that fails verification' => [60],
            'a failure while sending the first byte' => [55],
        ];
    }

    #[DataProvider('transferErrorsBeforeSending')]
    public function testATransferThatSentNothingStaysARefusal(int $errno): void
    {
        $core = $this->syncCoreWithResponses([$this->transferFailure($errno, 0)]);

        try {
            $core->sendRaw(new Request('POST', self::URL, [], '{}'), [], 1);
            $this->fail('The transfer failure was not reported.');
        } catch (SyncCoreException $e) {
            $this->assertNotInstanceOf(TimeoutException::class, $e);
            $this->assertStringContainsString('cURL error '.$errno, $e->getMessage());
        }
    }

    public function testATransferThatFailedAfterSendingFollowingAnAttemptThatNeverLeftIsATimeout(): void
    {
        $core = $this->syncCoreWithResponses([
            $this->connectionFailure(7, 0),
            $this->transferFailure(56, 312),
        ]);

        $exception = $this->failure($core, 2);

        $this->assertNotInstanceOf(ConnectionFailedException::class, $exception);
        $this->assertCount(2, $this->history);
    }

    /**
     * A connection failure as Guzzle's cURL handler reports it.
     */
    private function connectionFailure(int $errno, int $request_size): ConnectException
    {
        return new ConnectException(
            'cURL error '.$errno.': see https://curl.haxx.se/libcurl/c/libcurl-errors.html',
            new Request('POST', self::URL),
            null,
            ['errno' => $errno, 'error' => 'cURL error '.$errno, 'request_size' => $request_size],
        );
    }

    /**
     * A failure once the transfer is under way, as Guzzle's cURL handler
     * reports it.
     */
    private function transferFailure(int $errno, int $request_size): RequestException
    {
        return new RequestException(
            'cURL error '.$errno.': see https://curl.haxx.se/libcurl/c/libcurl-errors.html',
            new Request('POST', self::URL),
            null,
            null,
            ['errno' => $errno, 'error' => 'cURL error '.$errno, 'request_size' => $request_size],
        );
    }

    /**
     * The TimeoutException a request to the Sync Core fails with.
     */
    private function failure(SyncCore $core, int $retry_count): TimeoutException
    {
        try {
            $core->sendRaw(new Request('POST', self::URL, [], '{}'), [], $retry_count);
        } catch (TimeoutException $e) {
            return $e;
        }

        $this->fail('The request did not fail with a TimeoutException.');
    }
}
