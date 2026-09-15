<?php

declare(strict_types=1);

namespace EdgeBox\SyncCore\Tests;

use EdgeBox\SyncCore\Exception\ConflictException;
use EdgeBox\SyncCore\Exception\SyncCoreException;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;

/**
 * @internal
 */
final class ConflictExceptionTest extends SyncCoreTestCase
{
    public function testA409BecomesAConflictException(): void
    {
        $core = $this->syncCoreWithResponses([new Response(409, [], '{"message":"a live suggestion exists"}')]);

        try {
            $core->sendRaw(new Request('POST', 'https://core.example.com/sync-core/content-optimization/external'), [], 1);
            $this->fail('expected a ConflictException');
        } catch (ConflictException $e) {
            $this->assertSame(409, $e->getStatusCode());
        }
    }

    public function testAConflictExceptionIsCaughtBySyncCoreExceptionHandlers(): void
    {
        $core = $this->syncCoreWithResponses([new Response(409, [], '{}')]);

        $this->expectException(SyncCoreException::class);
        $core->sendRaw(new Request('POST', 'https://core.example.com/sync-core/content-optimization/external'), [], 1);
    }

    public function testA410RemainsAGenericSyncCoreException(): void
    {
        $core = $this->syncCoreWithResponses([new Response(410, [], '{"message":"gone"}')]);

        try {
            $core->sendRaw(new Request('GET', 'https://core.example.com/sync-core/thing'), [], 1);
            $this->fail('expected a SyncCoreException');
        } catch (ConflictException $e) {
            $this->fail('a 410 must not become a ConflictException');
        } catch (SyncCoreException $e) {
            $this->assertSame(410, $e->getStatusCode());
        }
    }
}
