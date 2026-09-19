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

    #[DataProvider('unusableFigures')]
    public function testAFigureTheBoxCouldNotUseIsLeftOutWhileTheRestArrive(array $case): void
    {
        $figures = self::everyFigure();
        $figures[$case['key']] = $case['value'];

        $everythingElse = (new PageFiguresBoxParams(self::everyFigure()))->toOptions();
        unset($everythingElse[$case['option']]);

        $options = (new PageFiguresBoxParams($figures))->toOptions();

        $this->assertArrayNotHasKey($case['option'], $options);
        $this->assertSame($everythingElse, $options, 'every other figure still arrives');
    }

    /**
     * @return array<string, array{0: array{key: string, value: mixed, option: string}}>
     */
    public static function unusableFigures(): array
    {
        $cases = [
            'a health above the scale' => ['content_health_percent_0_to_100', 150, 'contentHealthPercent'],
            'a health below the scale' => ['content_health_percent_0_to_100', -1, 'contentHealthPercent'],
            'a health that is no number at all' => ['content_health_percent_0_to_100', 'eighty-four', 'contentHealthPercent'],
            'a health that is not a whole number' => ['content_health_percent_0_to_100', '84.5', 'contentHealthPercent'],
            'a health given as nothing' => ['content_health_percent_0_to_100', null, 'contentHealthPercent'],
            'an empty summary' => ['content_health_summary', '', 'contentHealthSummary'],
            'a summary of whitespace' => ['content_health_summary', "  \n ", 'contentHealthSummary'],
            'a summary that is no sentence' => ['content_health_summary', 42, 'contentHealthSummary'],
            'a summary longer than the box holds one to' => ['content_health_summary', self::ofLength(PageFiguresBoxParams::MAX_SUMMARY_LENGTH + 1), 'contentHealthSummary'],
            'a negative issue count' => ['open_issue_count', -1, 'openIssueCount'],
            'an issue count that is no number' => ['open_issue_count', true, 'openIssueCount'],
            'a count larger than a whole number holds' => ['open_issue_count', '9223372036854775808', 'openIssueCount'],
            'a count with a digit in front of it' => ['open_issue_count', '007', 'openIssueCount'],
            'a count with a sign in front of it' => ['open_issue_count', '+3', 'openIssueCount'],
            'a count with a line break after it' => ['open_issue_count', "3\n", 'openIssueCount'],
            'a negative citation count' => ['cited_in_answers_last_30_days', -4, 'citedInAnswersLast30Days'],
            'a priority between the ones there are' => ['content_priority', 250, 'contentPriority'],
            'a priority written as a word' => ['content_priority', 'high', 'contentPriority'],
            'a stamp that is no number' => ['summary_updated', 'yesterday', 'summaryUpdated'],
            'a stamp before the epoch' => ['summary_updated', -1, 'summaryUpdated'],
            'a stamp in milliseconds' => ['summary_updated', 1758240000000, 'summaryUpdated'],
        ];

        $named = [];
        foreach ($cases as $what => [$key, $value, $option]) {
            $named[$what] = [['key' => $key, 'value' => $value, 'option' => $option]];
        }

        return $named;
    }

    #[DataProvider('figuresStoredAsDigits')]
    public function testAFigureItsStorageHandsBackAsDigitsTravelsAsItsNumber(array $case): void
    {
        $figures = self::everyFigure();
        $figures[$case['key']] = $case['value'];

        $options = (new PageFiguresBoxParams($figures))->toOptions();

        $this->assertSame($case['number'], $options[$case['option']]);
    }

    /**
     * @return array<string, array{0: array{key: string, value: string, option: string, number: int}}>
     */
    public static function figuresStoredAsDigits(): array
    {
        $cases = [
            'a health' => ['content_health_percent_0_to_100', '84', 'contentHealthPercent', 84],
            'an issue count' => ['open_issue_count', '3', 'openIssueCount', 3],
            'nothing counted' => ['open_issue_count', '0', 'openIssueCount', 0],
            'a priority' => ['content_priority', '400', 'contentPriority', 400],
            'a citation count' => ['cited_in_answers_last_30_days', '12', 'citedInAnswersLast30Days', 12],
            'a stamp' => ['summary_updated', '1758240000', 'summaryUpdated', 1758240000],
        ];

        $named = [];
        foreach ($cases as $what => [$key, $value, $option, $number]) {
            $named[$what] = [['key' => $key, 'value' => $value, 'option' => $option, 'number' => $number]];
        }

        return $named;
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
            ['key' => 'legal', 'name' => self::ofLength(PageFiguresBoxParams::MAX_TAG_LENGTH + 1)],
            ['key' => self::ofLength(PageFiguresBoxParams::MAX_TAG_LENGTH + 1), 'name' => 'Careers'],
            'not an entry',
            ['key' => 'security', 'name' => 'Security'],
        ];

        $options = (new PageFiguresBoxParams($figures))->toOptions();

        $this->assertSame([['key' => 'security', 'name' => 'Security']], $options['tags']);
    }

    public function testATagIsSentTrimmedAndAtItsFullAllowedLength(): void
    {
        $longest = self::ofLength(PageFiguresBoxParams::MAX_TAG_LENGTH);

        $figures = self::everyFigure();
        $figures['tags'] = [['key' => '  pricing  ', 'name' => $longest]];

        $options = (new PageFiguresBoxParams($figures))->toOptions();

        $this->assertSame([['key' => 'pricing', 'name' => $longest]], $options['tags']);
    }

    public function testEveryTagThePageCarriesIsPassedOn(): void
    {
        $many = [];
        for ($index = 0; $index < 25; ++$index) {
            $many[] = ['key' => 'tag-'.$index, 'name' => 'Tag '.$index];
        }

        $figures = self::everyFigure();
        $figures['tags'] = $many;

        $options = (new PageFiguresBoxParams($figures))->toOptions();

        // The box decides how many it shows and says how many it left out, so
        // cutting the list here would hide from a reader that there are more.
        $this->assertCount(25, $options['tags']);
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

    #[DataProvider('noTags')]
    public function testAPageWithoutTagsSaysSoRatherThanSayingNothing(array $case): void
    {
        $figures = self::everyFigure();

        if ($case['absent']) {
            unset($figures['tags']);
        } else {
            $figures['tags'] = $case['given'];
        }

        $options = (new PageFiguresBoxParams($figures))->toOptions();

        $this->assertSame([], $options['tags']);
    }

    /**
     * @return array<string, array{0: array{absent: bool, given: mixed}}>
     */
    public static function noTags(): array
    {
        return [
            'absent' => [['absent' => true, 'given' => null]],
            'empty' => [['absent' => false, 'given' => []]],
            'not a list' => [['absent' => false, 'given' => 'pricing']],
        ];
    }

    public function testACallerCannotReachTheFramesOwnOptions(): void
    {
        $figures = self::everyFigure();
        $figures['embedSize'] = 'line';
        $figures['jwt'] = 'caller-supplied';
        $figures['configurationAccess'] = true;
        $figures['debug'] = 'yes';

        $options = (new PageFiguresBoxParams($figures))->toOptions();

        $this->assertArrayNotHasKey('embedSize', $options);
        $this->assertArrayNotHasKey('jwt', $options);
        $this->assertArrayNotHasKey('configurationAccess', $options);
        $this->assertArrayNotHasKey('debug', $options);
    }

    public function testAStampIsWholeEpochSecondsFromTheEpochToAYearAhead(): void
    {
        $accepted = [
            'the epoch itself' => 0,
            'a moment in the past' => 1758240000,
            'the furthest a clock that runs fast reaches' => time() + PageFiguresBoxParams::MAX_SECONDS_AHEAD_OF_NOW - 1,
        ];

        foreach ($accepted as $what => $stamp) {
            $figures = self::everyFigure();
            $figures['summary_updated'] = $stamp;

            $options = (new PageFiguresBoxParams($figures))->toOptions();

            $this->assertSame($stamp, $options['summaryUpdated'] ?? null, $what.' is a time the box can show');
        }

        // Seconds, never milliseconds: a stamp in milliseconds lands tens of
        // thousands of years out, and one value the box refuses costs the page
        // every figure.
        $figures = self::everyFigure();
        $figures['summary_updated'] = 1758240000 * 1000;

        $this->assertArrayNotHasKey('summaryUpdated', (new PageFiguresBoxParams($figures))->toOptions());
    }

    public function testAPageNamedAtLengthIsRefusedRatherThanCostingEveryFigure(): void
    {
        foreach (['entity_type', 'entity_uuid', 'langcode'] as $key) {
            $figures = self::everyFigure();
            $figures[$key] = self::ofLength(PageFiguresBoxParams::MAX_IDENTITY_LENGTH + 1);

            try {
                new PageFiguresBoxParams($figures);
                $this->fail('a '.$key.' the box would refuse has to raise');
            } catch (\InvalidArgumentException $expected) {
                $this->assertStringContainsString($key, $expected->getMessage());
            }
        }
    }

    public function testThePageIsNamedByWhateverIdItsSystemHasForIt(): void
    {
        $figures = self::everyFigure();
        $figures['entity_uuid'] = '  417  ';

        $params = new PageFiguresBoxParams($figures);

        $this->assertSame('417', $params->getEntityUuid());
        $this->assertSame('417', $params->toOptions()['entityUuid']);
    }

    public function testThePageIsNamedTheWayAnyContentManagementSystemNamesOne(): void
    {
        $added = [
            __DIR__.'/../src/Interfaces/Embed/PageFiguresBoxParams.php',
            __DIR__.'/../src/V2/Embed/PageFiguresEmbed.php',
        ];

        foreach ($added as $file) {
            $source = (string) file_get_contents($file);

            foreach (['node', 'nid', 'drupal', 'wordpress'] as $foreign) {
                $this->assertStringNotContainsStringIgnoringCase($foreign, $source, basename($file).' names a content management system of its own');
            }
        }
    }

    /**
     * A text of exactly that many characters.
     */
    private static function ofLength(int $characters): string
    {
        return str_repeat('a', $characters);
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
