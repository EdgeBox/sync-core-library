<?php

declare(strict_types=1);

namespace EdgeBox\SyncCore\Tests;

use EdgeBox\SyncCore\Exception\NotFoundException;
use EdgeBox\SyncCore\V2\Raw\Model\EntityRemoteStatus;
use EdgeBox\SyncCore\V2\Raw\Model\MigrationSummary;
use EdgeBox\SyncCore\V2\Raw\Model\PreviewsListResponse;
use EdgeBox\SyncCore\V2\Raw\Model\PullDashboardConfiguration;
use EdgeBox\SyncCore\V2\Raw\Model\SyndicationEntityWithUsage;
use EdgeBox\SyncCore\V2\Raw\Model\TaskGroupEntity;
use EdgeBox\SyncCore\V2\Raw\Model\TaskGroupType;
use EdgeBox\SyncCore\V2\Raw\Model\TaskStatus;
use EdgeBox\SyncCore\V2\Raw\Model\TaskType;
use EdgeBox\SyncCore\V2\Syndication\SyndicationService;
use GuzzleHttp\Exception\ConnectException;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\Attributes\DataProvider;

/**
 * The reads of the pull dashboard and of tasks return the generated models of
 * their routes.
 *
 * The service is instantiated directly rather than through
 * getSyndicationService(), whose method-level static cache would otherwise bind
 * one service to the first test's SyncCore for the rest of the process.
 *
 * @internal
 */
final class SyndicationServiceReadsTest extends SyncCoreTestCase
{
    private const FLOW_ID = 'articles';

    private const TASK_ID = '111111111111111111111111';

    private const TASK_GROUP_ID = '222222222222222222222222';

    private const CONTENT_SCOPES = ['content'];

    private const CONFIGURATION_SCOPES = ['configuration', 'content'];

    public function testThePullDashboardConfigurationIsReadWithTheScopeTheCallerPasses(): void
    {
        $answer = ['flows' => [['name' => 'Articles', 'machineName' => self::FLOW_ID, 'pools' => [['name' => 'Content', 'machineName' => 'content']], 'entityTypes' => []]]];

        $configuration = $this->service([$this->json($answer)])->getPullDashboardConfiguration(true);

        $this->assertInstanceOf(PullDashboardConfiguration::class, $configuration);
        $this->assertCount(1, $configuration->getFlows());
        $this->assertSame(self::FLOW_ID, $configuration->getFlows()[0]->getMachineName());
        $this->assertSame('content', $configuration->getFlows()[0]->getPools()[0]->getMachineName());
        $this->assertSame('GET', $this->sentRequest()->getMethod());
        $this->assertSame('/sync-core/configuration/pull-dashboard', $this->sentRequest()->getUri()->getPath());
        $this->assertSame(self::CONFIGURATION_SCOPES, $this->decodeBearer($this->sentRequest())['scopes']);
    }

    public function testThePullDashboardConfigurationIsReadInTheContentScopeWithoutConfigurationAccess(): void
    {
        $configuration = $this->service([$this->json(['flows' => []])])->getPullDashboardConfiguration(false);

        $this->assertSame([], $configuration->getFlows());
        $this->assertSame(self::CONTENT_SCOPES, $this->decodeBearer($this->sentRequest())['scopes']);
    }

    public function testThePreviewsAreListedWithEveryFilterTheCallerSets(): void
    {
        $answer = [
            'items' => [['entity' => ['name' => 'Welcome'], 'sourceUsage' => ['viewUrl' => 'https://source.example.com/node/1']]],
            'page' => 1,
            'numberOfPages' => 3,
            'itemsPerPage' => 5,
            'totalNumberOfItems' => 11,
        ];

        $page = $this->service([$this->json($answer)])->listPreviews(self::FLOW_ID, [
            'search' => 'Welcome',
            'entityTypeNamespaceMachineNames' => 'node',
            'entityTypeMachineNames' => 'article',
            'poolMachineNames' => 'content',
            'sourceSiteId' => 'source',
            'publishedEarliest' => '1700000000000',
            'publishedLatest' => '1800000000000',
            'existsLocally' => 'false',
            'deleted' => 'false',
            'deletedLocally' => 'true',
            'page' => '1',
            'itemsPerPage' => '5',
        ], false);

        $this->assertInstanceOf(PreviewsListResponse::class, $page);
        $this->assertCount(1, $page->getItems());
        $this->assertSame('https://source.example.com/node/1', $page->getItems()[0]->getSourceUsage()->getViewUrl());
        $this->assertEquals(3, $page->getNumberOfPages());
        $this->assertEquals(11, $page->getTotalNumberOfItems());
        $this->assertSame('/sync-core/previews/'.self::FLOW_ID, $this->sentRequest()->getUri()->getPath());
        parse_str($this->sentRequest()->getUri()->getQuery(), $query);
        $this->assertSame([
            'existsLocally' => 'false',
            'deletedLocally' => 'true',
            'deleted' => 'false',
            'sourceSiteId' => 'source',
            'entityTypeMachineNames' => 'article',
            'entityTypeNamespaceMachineNames' => 'node',
            'publishedLatest' => '1800000000000',
            'publishedEarliest' => '1700000000000',
            'poolMachineNames' => 'content',
            'itemsPerPage' => '5',
            'page' => '1',
            'search' => 'Welcome',
        ], $query);
        $this->assertSame(self::CONTENT_SCOPES, $this->decodeBearer($this->sentRequest())['scopes']);
    }

    public function testAFilterTheCallerLeavesOutOrSetsToNullOrEmptyOrZeroIsNotSent(): void
    {
        $this->service([$this->json(['items' => []])])->listPreviews(self::FLOW_ID, ['search' => null, 'sourceSiteId' => '', 'deleted' => '0', 'unknownFilter' => 'x', 'page' => '2'], true);

        parse_str($this->sentRequest()->getUri()->getQuery(), $query);
        $this->assertSame(['page' => '2'], $query);
        $this->assertSame(self::CONFIGURATION_SCOPES, $this->decodeBearer($this->sentRequest())['scopes']);
    }

    public function testATaskIsReadWithTheUsageOfItsItemInTheContentScope(): void
    {
        $answer = [
            'id' => self::TASK_ID,
            'type' => 'retrieve-entity',
            'status' => '400-finished',
            'usage' => ['status' => 'exists', 'viewUrl' => 'https://site.example.com/node/1'],
        ];

        $task = $this->service([$this->json($answer)])->getTask(self::TASK_ID);

        $this->assertInstanceOf(SyndicationEntityWithUsage::class, $task);
        $this->assertSame(self::TASK_ID, $task->getId());
        $this->assertSame(TaskType::RETRIEVE_ENTITY, $task->getType());
        $this->assertSame(TaskStatus::_400_FINISHED, $task->getStatus());
        $this->assertSame(EntityRemoteStatus::EXISTS, $task->getUsage()->getStatus());
        $this->assertSame('https://site.example.com/node/1', $task->getUsage()->getViewUrl());
        $this->assertSame('/sync-core/task/'.self::TASK_ID, $this->sentRequest()->getUri()->getPath());
        $this->assertSame('includeUsage=true', $this->sentRequest()->getUri()->getQuery());
        $this->assertSame(self::CONTENT_SCOPES, $this->decodeBearer($this->sentRequest())['scopes']);
    }

    public function testATaskGroupIsReadInTheContentScopeWithoutConfigurationAccess(): void
    {
        $answer = ['id' => self::TASK_GROUP_ID, 'type' => 'pull-all', 'status' => '200-running', 'flowMachineName' => self::FLOW_ID];

        $group = $this->service([$this->json($answer)])->getTaskGroup(self::TASK_GROUP_ID, false);

        $this->assertInstanceOf(TaskGroupEntity::class, $group);
        $this->assertSame(self::TASK_GROUP_ID, $group->getId());
        $this->assertSame(TaskGroupType::PULL_ALL, $group->getType());
        $this->assertSame(TaskStatus::_200_RUNNING, $group->getStatus());
        $this->assertSame(self::FLOW_ID, $group->getFlowMachineName());
        $this->assertSame('/sync-core/migration/'.self::TASK_GROUP_ID, $this->sentRequest()->getUri()->getPath());
        $this->assertSame(self::CONTENT_SCOPES, $this->decodeBearer($this->sentRequest())['scopes']);
    }

    public function testATaskGroupIsReadInTheConfigurationScopeWithConfigurationAccess(): void
    {
        $group = $this->service([$this->json(['id' => self::TASK_GROUP_ID, 'type' => 'pull-all', 'status' => '400-finished'])])->getTaskGroup(self::TASK_GROUP_ID, true);

        $this->assertSame(TaskStatus::_400_FINISHED, $group->getStatus());
        $this->assertSame(self::CONFIGURATION_SCOPES, $this->decodeBearer($this->sentRequest())['scopes']);
    }

    public function testATaskGroupSummaryIsReadInTheConfigurationScope(): void
    {
        $answer = ['byStatus' => [['status' => '400-finished', 'count' => 3], ['status' => '500-failed', 'count' => 1]]];

        $summary = $this->service([$this->json($answer)])->getTaskGroupSummary(self::TASK_GROUP_ID);

        $this->assertInstanceOf(MigrationSummary::class, $summary);
        $this->assertCount(2, $summary->getByStatus());
        $this->assertSame(TaskStatus::_400_FINISHED, $summary->getByStatus()[0]->getStatus());
        $this->assertEquals(3, $summary->getByStatus()[0]->getCount());
        $this->assertSame(TaskStatus::_500_FAILED, $summary->getByStatus()[1]->getStatus());
        $this->assertEquals(1, $summary->getByStatus()[1]->getCount());
        $this->assertSame('/sync-core/migration/'.self::TASK_GROUP_ID.'/summary', $this->sentRequest()->getUri()->getPath());
        $this->assertSame(self::CONFIGURATION_SCOPES, $this->decodeBearer($this->sentRequest())['scopes']);
    }

    public function testTheNewestManualPushOfThisSiteThroughTheFlowIsReadInTheContentScope(): void
    {
        $newest = ['id' => self::TASK_GROUP_ID, 'type' => 'push-manually', 'status' => '200-running'];
        $older = ['id' => '333333333333333333333333', 'type' => 'push-manually', 'status' => '400-finished'];
        $service = $this->service([$this->json(['items' => [$newest, $older], 'numberOfPages' => 4])]);

        $group = $service->getNewestManualPushTaskGroup(self::FLOW_ID);

        $this->assertInstanceOf(TaskGroupEntity::class, $group);
        $this->assertSame(self::TASK_GROUP_ID, $group->getId());
        $this->assertSame(TaskGroupType::PUSH_MANUALLY, $group->getType());
        $this->assertSame('/sync-core/migration', $this->sentRequest()->getUri()->getPath());
        parse_str($this->sentRequest()->getUri()->getQuery(), $query);
        $this->assertSame([
            'flowMachineName' => self::FLOW_ID,
            'types' => 'push-manually',
            'siteUuid' => $this->application->siteUuid,
            'itemsPerPage' => '1',
        ], $query);
        $this->assertSame(self::CONTENT_SCOPES, $this->decodeBearer($this->sentRequest())['scopes']);
    }

    public function testWithoutAManualPushThroughTheFlowThereIsNoNewestOne(): void
    {
        $this->assertNull($this->service([$this->json(['items' => []])])->getNewestManualPushTaskGroup(self::FLOW_ID));
        $this->assertNull($this->service([$this->json(['numberOfPages' => 0])])->getNewestManualPushTaskGroup(self::FLOW_ID));
    }

    /**
     * @return array<string, array{\Closure(SyndicationService): mixed}>
     */
    public static function reads(): array
    {
        return [
            'the pull dashboard configuration' => [static fn (SyndicationService $service) => $service->getPullDashboardConfiguration(false)],
            'the previews' => [static fn (SyndicationService $service) => $service->listPreviews(self::FLOW_ID, [], false)],
            'a task' => [static fn (SyndicationService $service) => $service->getTask(self::TASK_ID)],
            'a task group' => [static fn (SyndicationService $service) => $service->getTaskGroup(self::TASK_GROUP_ID, false)],
            'a task group summary' => [static fn (SyndicationService $service) => $service->getTaskGroupSummary(self::TASK_GROUP_ID)],
            'the newest manual push' => [static fn (SyndicationService $service) => $service->getNewestManualPushTaskGroup(self::FLOW_ID)],
        ];
    }

    #[DataProvider('reads')]
    public function testAReadIsSentAgainAfterAConnectionFailure(\Closure $read): void
    {
        $service = $this->service([
            new ConnectException('Connection refused', new Request('GET', 'https://core.example.com')),
            $this->json(['items' => [['id' => self::TASK_GROUP_ID]]]),
        ]);

        $this->assertNotNull($read($service));
        $this->assertCount(2, $this->history);
    }

    /**
     * @return array<string, array{\Closure(SyndicationService, string): mixed}>
     */
    public static function readsById(): array
    {
        $cases = [];
        $reads = [
            'the previews of a Flow' => static fn (SyndicationService $service, string $id) => $service->listPreviews($id, [], false),
            'a task' => static fn (SyndicationService $service, string $id) => $service->getTask($id),
            'a task group' => static fn (SyndicationService $service, string $id) => $service->getTaskGroup($id, false),
            'a task group summary' => static fn (SyndicationService $service, string $id) => $service->getTaskGroupSummary($id),
            'the newest manual push through a Flow' => static fn (SyndicationService $service, string $id) => $service->getNewestManualPushTaskGroup($id),
        ];
        foreach ($reads as $name => $read) {
            foreach (['', '.', '..', '0'] as $id) {
                $cases[$name.' by the ID "'.$id.'"'] = [$read, $id];
            }
        }

        return $cases;
    }

    #[DataProvider('readsById')]
    public function testAReadByAnIdThatNamesNoRecordIsRefusedBeforeItIsSent(\Closure $read, string $id): void
    {
        $service = $this->service([$this->json(['items' => []])]);

        try {
            $read($service, $id);
            $this->fail('The ID was not refused.');
        } catch (\InvalidArgumentException $e) {
            $this->assertCount(0, $this->history);
        }
    }

    public function testARefusalStillThrows(): void
    {
        $service = $this->service([new Response(404, [], json_encode(['message' => 'No such task.']))]);

        $this->expectException(NotFoundException::class);
        $service->getTask(self::TASK_ID);
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
