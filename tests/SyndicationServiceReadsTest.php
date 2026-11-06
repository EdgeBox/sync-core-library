<?php

declare(strict_types=1);

namespace EdgeBox\SyncCore\Tests;

use EdgeBox\SyncCore\Exception\NotFoundException;
use EdgeBox\SyncCore\Interfaces\IApplicationInterface;
use EdgeBox\SyncCore\V2\Syndication\SyndicationService;
use GuzzleHttp\Exception\ConnectException;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;

/**
 * The reads of the pull dashboard and of tasks return the JSON the Sync Core
 * answered with, so a field it leaves out or a status this library does not
 * know never throws.
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

    private const UNKNOWN_STATUS = '999-not-yet-known';

    public function testThePullDashboardConfigurationIsReadWithTheScopeTheCallerPasses(): void
    {
        $answer = ['flows' => [['machineName' => self::FLOW_ID, 'pools' => [['machineName' => 'content']]]]];

        $configuration = $this->service([$this->json($answer)])->getPullDashboardConfiguration(true);

        $this->assertSame($answer, $configuration);
        $this->assertSame('GET', $this->sentRequest()->getMethod());
        $this->assertSame('/sync-core/configuration/pull-dashboard', $this->sentRequest()->getUri()->getPath());
        $this->assertSame([IApplicationInterface::SYNC_CORE_PERMISSIONS_CONFIGURATION, IApplicationInterface::SYNC_CORE_PERMISSIONS_CONTENT], $this->decodeBearer($this->sentRequest())['scopes']);
    }

    public function testThePullDashboardConfigurationWithoutFlowsIsReturnedWithoutThem(): void
    {
        $configuration = $this->service([$this->json(['entityTypes' => []])])->getPullDashboardConfiguration(false);

        $this->assertArrayNotHasKey('flows', $configuration);
        $this->assertSame([IApplicationInterface::SYNC_CORE_PERMISSIONS_CONTENT], $this->decodeBearer($this->sentRequest())['scopes']);
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

        $this->assertSame($answer, $page);
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
        $this->assertSame([IApplicationInterface::SYNC_CORE_PERMISSIONS_CONTENT], $this->decodeBearer($this->sentRequest())['scopes']);
    }

    public function testAFilterTheCallerLeavesOutOrSetsToNullIsNotSent(): void
    {
        $this->service([$this->json(['items' => []])])->listPreviews(self::FLOW_ID, ['search' => null, 'page' => '2'], true);

        parse_str($this->sentRequest()->getUri()->getQuery(), $query);
        $this->assertSame(['page' => '2'], $query);
    }

    public function testAPreviewPageWithoutItsCountsIsReturnedWithoutThem(): void
    {
        $page = $this->service([$this->json(['items' => [['entity' => []]]])])->listPreviews(self::FLOW_ID, [], false);

        $this->assertSame([['entity' => []]], $page['items']);
        $this->assertArrayNotHasKey('numberOfPages', $page);
    }

    public function testATaskIsReadWithTheUsageOfItsItemInTheContentScope(): void
    {
        $answer = [
            'id' => self::TASK_ID,
            'type' => 'pull',
            'status' => '400-finished',
            'usage' => ['status' => 'exists', 'viewUrl' => 'https://site.example.com/node/1'],
        ];

        $task = $this->service([$this->json($answer)])->getTask(self::TASK_ID);

        $this->assertSame($answer, $task);
        $this->assertSame('/sync-core/task/'.self::TASK_ID, $this->sentRequest()->getUri()->getPath());
        $this->assertSame('includeUsage=true', $this->sentRequest()->getUri()->getQuery());
        $this->assertSame([IApplicationInterface::SYNC_CORE_PERMISSIONS_CONTENT], $this->decodeBearer($this->sentRequest())['scopes']);
    }

    public function testATaskWithAnUnknownStatusAndWithoutItsUsageIsReturnedAsSent(): void
    {
        $task = $this->service([$this->json(['id' => self::TASK_ID, 'status' => self::UNKNOWN_STATUS])])->getTask(self::TASK_ID);

        $this->assertSame(self::UNKNOWN_STATUS, $task['status']);
        $this->assertArrayNotHasKey('usage', $task);
        $this->assertArrayNotHasKey('type', $task);
    }

    public function testATaskGroupIsReadWithTheScopeTheCallerPasses(): void
    {
        $answer = ['id' => self::TASK_GROUP_ID, 'type' => 'pull-all', 'status' => '200-running', 'flowMachineName' => self::FLOW_ID];

        $group = $this->service([$this->json($answer)])->getTaskGroup(self::TASK_GROUP_ID, false);

        $this->assertSame($answer, $group);
        $this->assertSame('/sync-core/migration/'.self::TASK_GROUP_ID, $this->sentRequest()->getUri()->getPath());
        $this->assertSame([IApplicationInterface::SYNC_CORE_PERMISSIONS_CONTENT], $this->decodeBearer($this->sentRequest())['scopes']);
    }

    public function testATaskGroupWithAnUnknownStatusAndTypeAndWithoutItsFlowIsReturnedAsSent(): void
    {
        $group = $this->service([$this->json(['id' => self::TASK_GROUP_ID, 'type' => 'not-yet-known', 'status' => self::UNKNOWN_STATUS])])->getTaskGroup(self::TASK_GROUP_ID, true);

        $this->assertSame(self::UNKNOWN_STATUS, $group['status']);
        $this->assertSame('not-yet-known', $group['type']);
        $this->assertArrayNotHasKey('flowMachineName', $group);
        $this->assertSame([IApplicationInterface::SYNC_CORE_PERMISSIONS_CONFIGURATION, IApplicationInterface::SYNC_CORE_PERMISSIONS_CONTENT], $this->decodeBearer($this->sentRequest())['scopes']);
    }

    public function testATaskGroupSummaryIsReadInTheConfigurationScope(): void
    {
        $answer = ['byStatus' => [['status' => '400-finished', 'count' => 3], ['status' => self::UNKNOWN_STATUS, 'count' => 1], ['status' => '500-failed']]];

        $summary = $this->service([$this->json($answer)])->getTaskGroupSummary(self::TASK_GROUP_ID);

        $this->assertSame($answer, $summary);
        $this->assertSame('/sync-core/migration/'.self::TASK_GROUP_ID.'/summary', $this->sentRequest()->getUri()->getPath());
        $this->assertSame([IApplicationInterface::SYNC_CORE_PERMISSIONS_CONFIGURATION, IApplicationInterface::SYNC_CORE_PERMISSIONS_CONTENT], $this->decodeBearer($this->sentRequest())['scopes']);
    }

    public function testTheNewestManualPushOfThisSiteThroughTheFlowIsReadInTheContentScope(): void
    {
        $newest = ['id' => self::TASK_GROUP_ID, 'type' => 'push-manually', 'status' => self::UNKNOWN_STATUS];
        $service = $this->service([$this->json(['items' => [$newest], 'numberOfPages' => 4])]);

        $group = $service->getNewestManualPushTaskGroup(self::FLOW_ID);

        $this->assertSame($newest, $group);
        $this->assertSame('/sync-core/migration', $this->sentRequest()->getUri()->getPath());
        parse_str($this->sentRequest()->getUri()->getQuery(), $query);
        $this->assertSame([
            'flowMachineName' => self::FLOW_ID,
            'types' => 'push-manually',
            'siteUuid' => $this->application->siteUuid,
            'itemsPerPage' => '1',
        ], $query);
        $this->assertSame([IApplicationInterface::SYNC_CORE_PERMISSIONS_CONTENT], $this->decodeBearer($this->sentRequest())['scopes']);
    }

    public function testWithoutAManualPushThroughTheFlowThereIsNoNewestOne(): void
    {
        $this->assertNull($this->service([$this->json(['items' => []])])->getNewestManualPushTaskGroup(self::FLOW_ID));
        $this->assertNull($this->service([$this->json(['numberOfPages' => 0])])->getNewestManualPushTaskGroup(self::FLOW_ID));
    }

    public function testAnAnswerThatIsNotAJsonObjectIsAnEmptyArray(): void
    {
        $this->assertSame([], $this->service([new Response(200, [], 'null')])->getTaskGroupSummary(self::TASK_GROUP_ID));
    }

    public function testAReadIsSentAgainAfterAConnectionFailure(): void
    {
        $service = $this->service([
            new ConnectException('Connection refused', new Request('GET', 'https://core.example.com')),
            $this->json(['id' => self::TASK_ID]),
        ]);

        $this->assertSame(['id' => self::TASK_ID], $service->getTask(self::TASK_ID));
        $this->assertCount(2, $this->history);
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
