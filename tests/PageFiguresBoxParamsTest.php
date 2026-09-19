<?php

declare(strict_types=1);

namespace EdgeBox\SyncCore\Tests;

use EdgeBox\SyncCore\Interfaces\Embed\PageFiguresBoxParams;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 */
final class PageFiguresBoxParamsTest extends TestCase
{
    public function testEveryFigureReachesTheBoxUnderTheNameItReadsItBy(): void
    {
        $options = (new PageFiguresBoxParams(self::everyFigure()))->toOptions();

        $this->assertSame([
            'entityType' => 'node',
            'entityUuid' => 'f1b0c0de-0000-4000-8000-000000000001',
            'langcode' => 'de',
            'contentHealthPercent' => 84,
            'contentHealthSummary' => 'The page answers the question it ranks for.',
            'openIssueCount' => 3,
            'contentPriority' => PageFiguresBoxParams::PRIORITY_HIGH,
            'citedInAnswersLast30Days' => 12,
            'summaryUpdated' => 1758240000,
            'tags' => [
                ['key' => 'pricing', 'name' => 'Pricing'],
                ['key' => 'onboarding', 'name' => 'Onboarding'],
            ],
        ], $options);
    }

    public function testThePageIsNamedByItsOwnParts(): void
    {
        $params = new PageFiguresBoxParams(self::everyFigure());

        $this->assertSame('node', $params->getEntityType());
        $this->assertSame('f1b0c0de-0000-4000-8000-000000000001', $params->getEntityUuid());
        $this->assertSame('de', $params->getLangcode());
    }

    #[DataProvider('unusableIdentities')]
    public function testAPageThatNamesItselfIncompletelyIsRefused(array $figures): void
    {
        $this->expectException(\InvalidArgumentException::class);

        new PageFiguresBoxParams($figures);
    }

    /**
     * @return array<string, array{0: array}>
     */
    public static function unusableIdentities(): array
    {
        $cases = [];

        foreach (['entity_type', 'entity_uuid', 'langcode'] as $key) {
            foreach (['missing' => null, 'empty' => '', 'not a string' => 42] as $how => $value) {
                $figures = self::everyFigure();

                if (null === $value) {
                    unset($figures[$key]);
                } else {
                    $figures[$key] = $value;
                }

                $cases[$key.' '.$how] = [$figures];
            }
        }

        return $cases;
    }

    /**
     * @param mixed $value
     */
    #[DataProvider('unusableFigures')]
    public function testAFigureTheBoxCouldNotUseIsLeftOutWhileTheRestArrive(string $key, $value, string $option): void
    {
        $figures = self::everyFigure();
        $figures[$key] = $value;

        $everythingElse = (new PageFiguresBoxParams(self::everyFigure()))->toOptions();
        unset($everythingElse[$option]);

        $options = (new PageFiguresBoxParams($figures))->toOptions();

        $this->assertArrayNotHasKey($option, $options);
        $this->assertSame($everythingElse, $options, 'every other figure still arrives');
    }

    /**
     * @return array<string, array{0: string, 1: mixed, 2: string}>
     */
    public static function unusableFigures(): array
    {
        return [
            'a health above the scale' => ['content_health_percent_0_to_100', 150, 'contentHealthPercent'],
            'a health below the scale' => ['content_health_percent_0_to_100', -1, 'contentHealthPercent'],
            'a health that is not a number' => ['content_health_percent_0_to_100', '84', 'contentHealthPercent'],
            'an empty summary' => ['content_health_summary', '', 'contentHealthSummary'],
            'a negative issue count' => ['open_issue_count', -1, 'openIssueCount'],
            'a negative citation count' => ['cited_in_answers_last_30_days', -4, 'citedInAnswersLast30Days'],
            'a priority between the ones there are' => ['content_priority', 250, 'contentPriority'],
            'a priority written as a word' => ['content_priority', 'high', 'contentPriority'],
            'a stamp that is not a number' => ['summary_updated', '1758240000', 'summaryUpdated'],
        ];
    }

    public function testAHealthAtEitherEndOfTheScaleIsAValue(): void
    {
        foreach ([0, 100] as $percent) {
            $figures = self::everyFigure();
            $figures['content_health_percent_0_to_100'] = $percent;

            $options = (new PageFiguresBoxParams($figures))->toOptions();

            $this->assertSame($percent, $options['contentHealthPercent']);
        }
    }

    public function testNothingCountedIsAValueRatherThanAnAbsence(): void
    {
        $figures = self::everyFigure();
        $figures['open_issue_count'] = 0;
        $figures['cited_in_answers_last_30_days'] = 0;

        $options = (new PageFiguresBoxParams($figures))->toOptions();

        $this->assertSame(0, $options['openIssueCount']);
        $this->assertSame(0, $options['citedInAnswersLast30Days']);
    }

    public function testEveryPriorityThereIsTravelsAsItsNumber(): void
    {
        $priorities = [
            PageFiguresBoxParams::PRIORITY_LOW,
            PageFiguresBoxParams::PRIORITY_MEDIUM,
            PageFiguresBoxParams::PRIORITY_HIGH,
            PageFiguresBoxParams::PRIORITY_CRITICAL,
        ];

        foreach ($priorities as $priority) {
            $figures = self::everyFigure();
            $figures['content_priority'] = $priority;

            $options = (new PageFiguresBoxParams($figures))->toOptions();

            $this->assertSame($priority, $options['contentPriority']);
        }
    }

    public function testATagThatDoesNotNameItselfWholeIsLeftOut(): void
    {
        $figures = self::everyFigure();
        $figures['tags'] = [
            ['key' => 'pricing'],
            ['name' => 'Onboarding'],
            ['key' => 'support', 'name' => ''],
            ['key' => '', 'name' => 'Support'],
            'not an entry',
            ['key' => 'security', 'name' => 'Security'],
        ];

        $options = (new PageFiguresBoxParams($figures))->toOptions();

        $this->assertSame([['key' => 'security', 'name' => 'Security']], $options['tags']);
    }

    public function testATagCarriesNothingBesideItsKeyAndItsName(): void
    {
        $figures = self::everyFigure();
        $figures['tags'] = [
            ['key' => 'pricing', 'name' => 'Pricing', 'vocabulary' => 'topics', 'id' => 7],
        ];

        $options = (new PageFiguresBoxParams($figures))->toOptions();

        $this->assertSame([['key' => 'pricing', 'name' => 'Pricing']], $options['tags']);
    }

    /**
     * @param mixed $given
     */
    #[DataProvider('noTags')]
    public function testAPageWithoutTagsSaysSoRatherThanSayingNothing(string $how, $given): void
    {
        $figures = self::everyFigure();

        if ('absent' === $how) {
            unset($figures['tags']);
        } else {
            $figures['tags'] = $given;
        }

        $options = (new PageFiguresBoxParams($figures))->toOptions();

        $this->assertSame([], $options['tags']);
    }

    /**
     * @return array<string, array{0: string, 1: mixed}>
     */
    public static function noTags(): array
    {
        return [
            'absent' => ['absent', null],
            'empty' => ['given', []],
            'not a list' => ['given', 'pricing'],
        ];
    }

    public function testACallerCannotReachTheFramesOwnOptions(): void
    {
        $figures = self::everyFigure();
        $figures['embedSize'] = 'line';
        $figures['jwt'] = 'caller-supplied';
        $figures['configurationAccess'] = true;

        $options = (new PageFiguresBoxParams($figures))->toOptions();

        $this->assertArrayNotHasKey('embedSize', $options);
        $this->assertArrayNotHasKey('jwt', $options);
        $this->assertArrayNotHasKey('configurationAccess', $options);
    }

    public function testThePageIsNamedTheWayAnyContentManagementSystemNamesOne(): void
    {
        $added = [
            __DIR__.'/../src/Interfaces/Embed/PageFiguresBoxParams.php',
            __DIR__.'/../src/V2/Embed/PageFiguresEmbed.php',
        ];

        foreach ($added as $file) {
            $source = (string) file_get_contents($file);

            foreach (['node', 'nid', 'Drupal', 'WordPress'] as $foreign) {
                $this->assertStringNotContainsString($foreign, $source, basename($file).' names a content management system of its own');
            }
        }
    }

    /**
     * A page whose every figure arrived.
     */
    private static function everyFigure(): array
    {
        return [
            'entity_type' => 'node',
            'entity_uuid' => 'f1b0c0de-0000-4000-8000-000000000001',
            'langcode' => 'de',
            'content_health_percent_0_to_100' => 84,
            'content_health_summary' => 'The page answers the question it ranks for.',
            'open_issue_count' => 3,
            'content_priority' => PageFiguresBoxParams::PRIORITY_HIGH,
            'cited_in_answers_last_30_days' => 12,
            'summary_updated' => 1758240000,
            'tags' => [
                ['key' => 'pricing', 'name' => 'Pricing'],
                ['key' => 'onboarding', 'name' => 'Onboarding'],
            ],
        ];
    }
}
