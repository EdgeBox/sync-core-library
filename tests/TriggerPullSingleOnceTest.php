<?php

declare(strict_types=1);

namespace EdgeBox\SyncCore\Tests;

use EdgeBox\SyncCore\Exception\TimeoutException;
use EdgeBox\SyncCore\Interfaces\Syndication\ITriggerPullSingle;
use EdgeBox\SyncCore\V2\Raw\Model\TaskEntity;
use EdgeBox\SyncCore\V2\Raw\Model\TaskStatus;
use EdgeBox\SyncCore\V2\Raw\Model\TaskType;
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

    private const UPPERCASE_UUID = 'AAAAAAAA-AAAA-4AAA-8AAA-AAAAAAAAAAAA';

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
            ->withUniqueId(self::UNIQUE_ID)
            ->manually(true)
        ;

        $task = $pull->executeOnce(true);

        $this->assertInstanceOf(TaskEntity::class, $task);
        $this->assertSame(self::TASK_ID, $task->getId());
        $this->assertSame(TaskType::RETRIEVE_ENTITY, $task->getType());
        $this->assertSame(TaskStatus::_100_INITIALIZING, $task->getStatus());
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

    public function testAPullThatNamesNoPoolIsSentWithoutAPoolList(): void
    {
        $this->service([$this->json(['id' => self::TASK_ID])])
            ->pullSingle(self::FLOW_ID, 'node', 'article', self::UUID)
            ->executeOnce(false)
        ;

        $body = $this->sentBody();
        $this->assertSame(self::FLOW_ID, $body['flowMachineName']);
        $this->assertArrayNotHasKey('poolMachineNames', $body);
    }

    public function testAPullThatNamesNoPoolIsExecutedWithoutAPoolList(): void
    {
        $pull = $this->service([$this->json(['id' => self::TASK_ID])])
            ->pullSingle(self::FLOW_ID, 'node', 'article', self::UUID)
        ;

        $pull->execute();

        $this->assertSame(self::TASK_ID, $pull->getId());
        $this->assertCount(1, $this->history);
        $body = $this->sentBody();
        $this->assertSame(self::FLOW_ID, $body['flowMachineName']);
        $this->assertSame(self::UUID, $body['remoteUuid']);
        $this->assertArrayNotHasKey('poolMachineNames', $body);
    }

    public function testAPullFromAPoolIsSentWithThatPool(): void
    {
        $this->service([$this->json(['id' => self::TASK_ID])])
            ->pullSingle(self::FLOW_ID, 'node', 'article', self::UUID)
            ->fromPool('content')
            ->executeOnce(false)
        ;

        $this->assertSame(['content'], $this->sentBody()['poolMachineNames']);
    }

    public function testATaskWithoutItsIdHasNoId(): void
    {
        $pull = $this->service([$this->json(['status' => '100-initializing'])])
            ->pullSingle(self::FLOW_ID, 'node', 'article', self::UUID)
        ;

        $task = $pull->executeOnce(false);

        $this->assertSame(TaskStatus::_100_INITIALIZING, $task->getStatus());
        $this->assertNull($task->getId());
        $this->assertNull($pull->getId());
    }

    /**
     * @return array<string, array{string, null|string, null|string, null|string, null|string}>
     */
    public static function idsByRole(): array
    {
        // The ID the pull is created with, the UUID and the unique ID named by
        // role, then the UUID and the unique ID the request carries.
        return [
            'a UUID, and a unique ID shaped like a UUID' => [self::UUID, null, self::OTHER_UUID, self::UUID, self::OTHER_UUID],
            'an uppercase UUID, and a unique ID' => [self::UPPERCASE_UUID, null, self::UNIQUE_ID, self::UPPERCASE_UUID, self::UNIQUE_ID],
            'an uppercase UUID named as the UUID' => [self::UPPERCASE_UUID, self::UPPERCASE_UUID, null, self::UPPERCASE_UUID, null],
            'an uppercase UUID named as the UUID in lower case' => [self::UPPERCASE_UUID, strtolower(self::UPPERCASE_UUID), null, strtolower(self::UPPERCASE_UUID), null],
            'a unique ID shaped like a UUID, and a UUID' => [self::OTHER_UUID, self::UUID, null, self::UUID, self::OTHER_UUID],
            'a unique ID shaped like a UUID named as the unique ID' => [self::OTHER_UUID, null, self::OTHER_UUID, null, self::OTHER_UUID],
            'a unique ID, and one ID named for both roles' => [self::UNIQUE_ID, self::UUID, self::UUID, self::UUID, self::UUID],
            'a unique ID, and both IDs named' => [self::UNIQUE_ID, self::UPPERCASE_UUID, self::UNIQUE_ID, self::UPPERCASE_UUID, self::UNIQUE_ID],
        ];
    }

    #[DataProvider('idsByRole')]
    public function testTheIdsNamedByRoleAreSentInTheirRoles(string $created_with, ?string $uuid, ?string $unique_id, ?string $sent_uuid, ?string $sent_unique_id): void
    {
        $pull = $this->service([$this->json(['id' => self::TASK_ID])])
            ->pullSingle(self::FLOW_ID, 'node', 'article', $created_with)
        ;
        if (null !== $uuid) {
            $pull->withUuid($uuid);
        }
        if (null !== $unique_id) {
            $pull->withUniqueId($unique_id);
        }

        $pull->executeOnce(false);

        $body = $this->sentBody();
        $this->assertSame($sent_uuid, $body['remoteUuid'] ?? null);
        $this->assertSame($sent_unique_id, $body['remoteUniqueId'] ?? null);
    }

    public function testThePullIsSentWithTheIdsInTheirRolesWhicheverIsNamedFirst(): void
    {
        $this->service([$this->json(['id' => self::TASK_ID])])
            ->pullSingle(self::FLOW_ID, 'node', 'article', self::UPPERCASE_UUID)
            ->withUniqueId(self::UNIQUE_ID)
            ->withUuid(self::UPPERCASE_UUID)
            ->executeOnce(false)
        ;

        $body = $this->sentBody();
        $this->assertSame(self::UPPERCASE_UUID, $body['remoteUuid']);
        $this->assertSame(self::UNIQUE_ID, $body['remoteUniqueId']);
    }

    /**
     * @return array<string, array{string, \Closure(ITriggerPullSingle): ITriggerPullSingle}>
     */
    public static function itemsCarryingBothIds(): array
    {
        return [
            'created with the unique ID, the UUID named' => [self::UNIQUE_ID, static fn (ITriggerPullSingle $pull) => $pull->withUuid(self::UUID)],
            'created with the UUID, the unique ID named' => [self::UUID, static fn (ITriggerPullSingle $pull) => $pull->withUniqueId(self::UNIQUE_ID)],
            'created with the UUID, both named' => [self::UUID, static fn (ITriggerPullSingle $pull) => $pull->withUuid(self::UUID)->withUniqueId(self::UNIQUE_ID)],
            'created with the unique ID, both named' => [self::UNIQUE_ID, static fn (ITriggerPullSingle $pull) => $pull->withUniqueId(self::UNIQUE_ID)->withUuid(self::UUID)],
        ];
    }

    /**
     * @param \Closure(ITriggerPullSingle): ITriggerPullSingle $name
     */
    #[DataProvider('itemsCarryingBothIds')]
    public function testAnItemCarryingBothIdsIsIdentifiedByItsUniqueId(string $created_with, \Closure $name): void
    {
        $name($this->service([$this->json(['id' => self::TASK_ID])])->pullSingle(self::FLOW_ID, 'node', 'article', $created_with))
            ->executeOnce(false)
        ;

        $body = $this->sentBody();
        $this->assertSame(self::UNIQUE_ID, $body['remoteUniqueId']);
        $this->assertSame(self::UUID, $body['remoteUuid']);
    }

    /**
     * @return array<string, array{string}>
     */
    public static function idsThatNameNoItem(): array
    {
        return ['empty' => [''], 'a dot' => ['.'], 'two dots' => ['..'], 'zero' => ['0']];
    }

    #[DataProvider('idsThatNameNoItem')]
    public function testAUuidThatNamesNoItemIsRefused(string $id): void
    {
        $pull = $this->service([$this->json(['id' => self::TASK_ID])])
            ->pullSingle(self::FLOW_ID, 'node', 'article', self::UNIQUE_ID)
        ;

        $this->expectException(\InvalidArgumentException::class);
        $pull->withUuid($id);
    }

    #[DataProvider('idsThatNameNoItem')]
    public function testAUniqueIdThatNamesNoItemIsRefused(string $id): void
    {
        $pull = $this->service([$this->json(['id' => self::TASK_ID])])
            ->pullSingle(self::FLOW_ID, 'node', 'article', self::UUID)
        ;

        $this->expectException(\InvalidArgumentException::class);
        $pull->withUniqueId($id);
    }

    #[DataProvider('idsThatNameNoItem')]
    public function testAPullCreatedWithAnIdThatNamesNoItemIsRefusedBeforeItIsSent(string $id): void
    {
        $pull = $this->service([$this->json(['id' => self::TASK_ID])])
            ->pullSingle(self::FLOW_ID, 'node', 'article', $id)
        ;

        try {
            $pull->executeOnce(false);
            $this->fail('The ID was not refused.');
        } catch (\InvalidArgumentException $e) {
            $this->assertCount(0, $this->history);
        }
    }

    #[DataProvider('idsThatNameNoItem')]
    public function testAPullThroughAFlowWhoseIdNamesNoFlowIsRefusedBeforeItIsSent(string $id): void
    {
        $pull = $this->service([$this->json(['id' => self::TASK_ID])])
            ->pullSingle($id, 'node', 'article', self::UUID)
        ;

        try {
            $pull->executeOnce(false);
            $this->fail('The Flow ID was not refused.');
        } catch (\InvalidArgumentException $e) {
            $this->assertCount(0, $this->history);
        }
    }

    #[DataProvider('idsThatNameNoItem')]
    public function testAPullFromAPoolWhoseIdNamesNoPoolIsRefusedBeforeItIsSent(string $id): void
    {
        $pull = $this->service([$this->json(['id' => self::TASK_ID])])
            ->pullSingle(self::FLOW_ID, 'node', 'article', self::UUID)
            ->fromPool($id)
        ;

        try {
            $pull->executeOnce(false);
            $this->fail('The Pool ID was not refused.');
        } catch (\InvalidArgumentException $e) {
            $this->assertCount(0, $this->history);
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
