<?php

declare(strict_types=1);

namespace EdgeBox\SyncCore\Tests;

use EdgeBox\SyncCore\Interfaces\Syndication\IMassPull;
use EdgeBox\SyncCore\Interfaces\Syndication\IMassPush;
use EdgeBox\SyncCore\V2\Raw\Model\TaskGroupType;
use EdgeBox\SyncCore\V2\Syndication\SyndicationService;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\Attributes\DataProvider;

/**
 * A mass push or pull names the task group it started, so its caller can read
 * the group's status by its ID.
 *
 * @internal
 */
final class MassUpdateMigrationIdTest extends SyncCoreTestCase
{
    private const FLOW_ID = 'articles';

    private const VERSION_ID = '1111111111111111';

    private const TASK_GROUP_ID = '222222222222222222222222';

    /**
     * @return array<string, array{\Closure(SyndicationService): (IMassPull|IMassPush), string}>
     */
    public static function massUpdates(): array
    {
        return [
            'a push of all content' => [static fn (SyndicationService $service) => $service->massPush(), TaskGroupType::PUSH_ALL],
            'a pull of all content' => [static fn (SyndicationService $service) => $service->massPull(), TaskGroupType::PULL_ALL],
        ];
    }

    /**
     * @param \Closure(SyndicationService): (IMassPull|IMassPush) $create
     */
    #[DataProvider('massUpdates')]
    public function testTheStartedTaskGroupIsNamedByItsId(\Closure $create, string $type): void
    {
        $service = $this->service([$this->json(['id' => self::TASK_GROUP_ID, 'type' => $type])]);
        $operation = $this->configured($create($service));

        $this->assertNull($operation->getMigrationId());

        $operation->execute();

        $this->assertSame(self::TASK_GROUP_ID, $operation->getMigrationId());
        $this->assertCount(1, $this->history);
        $this->assertSame('POST', $this->sentRequest()->getMethod());
        $this->assertSame('/sync-core/migration', $this->sentRequest()->getUri()->getPath());
        $this->assertSame($type, $this->sentBody()['type']);
    }

    /**
     * @param \Closure(SyndicationService): (IMassPull|IMassPush) $create
     */
    #[DataProvider('massUpdates')]
    public function testAnAnswerWithoutAnIdNamesNoTaskGroup(\Closure $create, string $type): void
    {
        $service = $this->service([$this->json(['type' => $type])]);
        $operation = $this->configured($create($service));

        $operation->execute();

        $this->assertNull($operation->getMigrationId());
    }

    /**
     * @param IMassPull|IMassPush $operation
     *
     * @return IMassPull|IMassPush
     */
    private function configured($operation)
    {
        return $operation
            ->withFlow(self::FLOW_ID)
            ->withNamespaceMachineName('node')
            ->withEntityTypeMachineName('article')
            ->withEntityTypeVersion(self::VERSION_ID)
        ;
    }

    /**
     * @param Response[] $responses
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
