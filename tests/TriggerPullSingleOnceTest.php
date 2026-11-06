<?php

declare(strict_types=1);

namespace EdgeBox\SyncCore\Tests;

use EdgeBox\SyncCore\Exception\SyncCoreException;
use EdgeBox\SyncCore\Exception\TimeoutException;
use EdgeBox\SyncCore\V2\Syndication\SyndicationService;
use GuzzleHttp\Exception\ConnectException;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\Attributes\DataProvider;

/**
 * A single pull sent once: never again after a connection failure, since
 * through a Flow that pulls copies a second request creates a second copy.
 *
 * @internal
 */
final class TriggerPullSingleOnceTest extends SyncCoreTestCase
{
    private const FLOW_ID = 'articles';

    private const UUID = '33333333-3333-4333-8333-333333333333';

    private const UNIQUE_ID = '4444';

    private const TASK_ID = '555555555555555555555555';

    private const OTHER_UUID = '66666666-6666-4666-8666-666666666666';

    public function testThePullIsSentOnceAfterAConnectionFailure(): void
    {
        $service = $this->service([
            new ConnectException('Connection refused', new Request('POST', 'https://core.example.com')),
            $this->json(['id' => self::TASK_ID]),
        ]);

        try {
            $service->pullSingle(self::FLOW_ID, 'node', 'article', self::UUID)->executeOnce(false);
            $this->fail('The connection failure was not reported.');
        } catch (TimeoutException $e) {
            $this->assertCount(1, $this->history);
            $this->assertSame(1, $this->mock->count());
        }
    }

    public function testThePullIsSentWithBothIdsAndTheScopeTheCallerPasses(): void
    {
        $answer = ['id' => self::TASK_ID, 'type' => 'retrieve-entity', 'status' => '100-initializing'];
        $pull = $this->service([$this->json($answer)])
            ->pullSingle(self::FLOW_ID, 'node', 'article', self::UUID)
            ->alsoKnownAs(self::UNIQUE_ID)
            ->manually(true)
        ;

        $task = $pull->executeOnce(true);

        $this->assertSame($answer, $task);
        $this->assertSame(self::TASK_ID, $pull->getId());
        $this->assertCount(1, $this->history);
        $this->assertSame('POST', $this->sentRequest()->getMethod());
        $this->assertSame('/sync-core/syndication', $this->sentRequest()->getUri()->getPath());
        $body = $this->sentBody();
        $this->assertSame(self::FLOW_ID, $body['flowMachineName']);
        $this->assertSame('node', $body['entityTypeNamespaceMachineName']);
        $this->assertSame('article', $body['entityTypeMachineName']);
        $this->assertSame(self::UUID, $body['remoteUuid']);
        $this->assertSame(self::UNIQUE_ID, $body['remoteUniqueId']);
        $this->assertTrue($body['manually']);
        $this->assertSame(['configuration', 'content'], $this->decodeBearer($this->sentRequest())['scopes']);
    }

    public function testThePullByItsUniqueIdAloneIsSentInTheContentScope(): void
    {
        $this->service([$this->json(['id' => self::TASK_ID])])
            ->pullSingle(self::FLOW_ID, 'node', 'article', self::UNIQUE_ID)
            ->executeOnce(false)
        ;

        $body = $this->sentBody();
        $this->assertSame(self::UNIQUE_ID, $body['remoteUniqueId']);
        $this->assertArrayNotHasKey('remoteUuid', $body);
        $this->assertSame(['content'], $this->decodeBearer($this->sentRequest())['scopes']);
    }

    public function testATaskWithAnUnknownStatusAndWithoutItsTypeIsReturnedAsSent(): void
    {
        $pull = $this->service([$this->json(['id' => self::TASK_ID, 'status' => '999-not-yet-known'])])
            ->pullSingle(self::FLOW_ID, 'node', 'article', self::UUID)
        ;

        $task = $pull->executeOnce(false);

        $this->assertSame('999-not-yet-known', $task['status']);
        $this->assertArrayNotHasKey('type', $task);
        $this->assertSame(self::TASK_ID, $pull->getId());
    }

    public function testATaskWithoutItsIdHasNoId(): void
    {
        $pull = $this->service([$this->json(['status' => '100-initializing'])])
            ->pullSingle(self::FLOW_ID, 'node', 'article', self::UUID)
        ;

        $this->assertSame(['status' => '100-initializing'], $pull->executeOnce(false));
        $this->assertNull($pull->getId());
    }

    public function testAUniqueIdShapedLikeAUuidIsSentAsTheUniqueIdOfAnItemCreatedWithItsUuid(): void
    {
        $this->service([$this->json(['id' => self::TASK_ID])])
            ->pullSingle(self::FLOW_ID, 'node', 'article', self::UUID)
            ->alsoKnownAs(self::OTHER_UUID)
            ->executeOnce(false)
        ;

        $body = $this->sentBody();
        $this->assertSame(self::UUID, $body['remoteUuid']);
        $this->assertSame(self::OTHER_UUID, $body['remoteUniqueId']);
    }

    public function testAnUppercaseUuidIsSentAsTheUuidOfAnItemCreatedWithItsUniqueId(): void
    {
        $uppercase_uuid = strtoupper(self::UUID);
        $this->service([$this->json(['id' => self::TASK_ID])])
            ->pullSingle(self::FLOW_ID, 'node', 'article', self::UNIQUE_ID)
            ->alsoKnownAs($uppercase_uuid)
            ->executeOnce(false)
        ;

        $body = $this->sentBody();
        $this->assertSame($uppercase_uuid, $body['remoteUuid']);
        $this->assertSame(self::UNIQUE_ID, $body['remoteUniqueId']);
    }

    /**
     * @return array<string, array{string}>
     */
    public static function idsThatNameNoItem(): array
    {
        return ['empty' => [''], 'a dot' => ['.'], 'two dots' => ['..']];
    }

    #[DataProvider('idsThatNameNoItem')]
    public function testAnOtherIdThatNamesNoItemIsRefused(string $id): void
    {
        $pull = $this->service([$this->json(['id' => self::TASK_ID])])
            ->pullSingle(self::FLOW_ID, 'node', 'article', self::UUID)
        ;

        $this->expectException(\InvalidArgumentException::class);
        $pull->alsoKnownAs($id);
    }

    public function testAnAnswerThatIsNotAJsonObjectThrowsAfterTheOneAttempt(): void
    {
        $pull = $this->service([new Response(201, [], '<html></html>'), $this->json(['id' => self::TASK_ID])])
            ->pullSingle(self::FLOW_ID, 'node', 'article', self::UUID)
        ;

        try {
            $pull->executeOnce(false);
            $this->fail('The unreadable answer was not reported.');
        } catch (SyncCoreException $e) {
            $this->assertCount(1, $this->history);
            $this->assertNull($pull->getId());
        }
    }

    /**
     * @param Response[]|\Throwable[] $responses
     */
    private function service(array $responses): SyndicationService
    {
        return new SyndicationService($this->syncCoreWithResponses($responses));
    }

    private function json(array $body): Response
    {
        return new Response(200, ['Content-Type' => 'application/json'], json_encode($body));
    }
}
