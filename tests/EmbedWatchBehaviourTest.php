<?php

declare(strict_types=1);

namespace EdgeBox\SyncCore\Tests;

use EdgeBox\SyncCore\Interfaces\Embed\PageFiguresBoxParams;
use EdgeBox\SyncCore\Tests\Support\EmbedMarkup;
use EdgeBox\SyncCore\V2\Embed\EmbedService;
use PHPUnit\Framework\TestCase;

/**
 * What the watch an embed emits does, run against stubbed engines.
 *
 * An assertion that names a substring of the emitted script survives the
 * destruction of what the substring is there for: the pieces are all still
 * emitted while the watch no longer keeps one wait at a time, no longer
 * spends one count on the frame, or no longer hears that the frame has gone.
 * So the watch is taken out of the rendered page as the page receives it,
 * handed to a stubbed document, clock and observers
 * (tests/Support/embed-watch-harness.js), and asked to behave.
 *
 * node is what runs it; a run without node fails rather than passing quietly,
 * because a gate nobody notices is off proves nothing.
 *
 * @internal
 */
final class EmbedWatchBehaviourTest extends TestCase
{
    public function testFourUncoveringsBeforeTheResizerAttachesStartOneChain(): void
    {
        $run = $this->perform('fourUncoveringsBeforeTheResizerAttaches');

        // Four uncoverings, no time passing between them: one measurement is
        // scheduled and it is the one that is pending.
        $this->assertSame(1, $run['scheduledAfterFourUncoverings']);
        $this->assertSame(1, $run['pendingAfterFourUncoverings']);

        // And the chain that one started is the bounded one.
        $this->assertSame(25, $run['scheduledInAll']);
        $this->assertSame(25, $run['firedInAll']);
        $this->assertSame(0, $run['pendingAtTheEnd']);
        $this->assertSame(0, $run['resizeCalls']);
        $this->assertTrue($run['watchStillOn'], 'a frame the document still holds keeps its watch');
    }

    public function testAFrameHiddenFromTheStartTakesTheWatchDownWhenItGoes(): void
    {
        $run = $this->perform('removedWhileHiddenFromTheStart');

        // Nothing reported an intersection for this frame in its whole life:
        // it was not intersecting when it was first seen and it was not
        // intersecting when the page let go of it.
        $this->assertSame(0, $run['intersectionReports']);

        // What hears of the removal is the change to the page's own tree.
        // Which node the watch asked to hear about, and which kind of change,
        // is not read off the observer here: the stub hands an observer only
        // the records it asked for, so an observer on the wrong node or with
        // the wrong options hears nothing and the take-down below never runs.
        $this->assertTrue($run['removalsAreWatchedFor'], 'the watch listens for changes to the tree');

        // And everything the watch set up is down before anything else runs.
        $this->assertTrue($run['intersectionDisconnected']);
        $this->assertTrue($run['removalsDisconnected']);
        $this->assertSame(0, $run['toggleListeners']);
        $this->assertSame(0, $run['pendingTimers']);
        $this->assertSame(0, $run['firedInAll']);
        $this->assertSame(0, $run['resizeCalls']);
    }

    public function testAFrameThatLeavesBecauseItsContainerDidTakesTheWatchDown(): void
    {
        $run = $this->perform('removedWithItsContainer');

        // What a rebuilt form takes out is the region, not the frame: the
        // frame still has a parent the whole time, and what decides is
        // whether the document holds it rather than whether it has one.
        $this->assertTrue($run['frameStillHasAParent']);
        $this->assertFalse($run['frameStillInTheDocument']);

        $this->assertTrue($run['intersectionDisconnected']);
        $this->assertTrue($run['removalsDisconnected']);
        $this->assertSame(0, $run['toggleListeners']);
        $this->assertSame(0, $run['pendingTimers']);
        $this->assertSame(0, $run['resizeCalls']);
    }

    public function testAFrameMovedWithinOneChangeKeepsItsWatch(): void
    {
        $run = $this->perform('movedWithinOneChange');

        // Taken out of one parent and put into another inside one change:
        // the document holds it when the watch is asked, so nothing comes
        // down and the frame is still measured when it is uncovered.
        $this->assertSame(1, $run['removalsHeardOf']);
        $this->assertTrue($run['frameInTheDocument']);
        $this->assertFalse($run['intersectionDisconnected']);
        $this->assertFalse($run['removalsDisconnected']);
        $this->assertSame(1, $run['pendingAfterItIsUncovered']);
    }

    public function testAFrameTakenOutWhileAWaitRunsStopsIt(): void
    {
        $run = $this->perform('removedWhileAWaitRuns');

        $this->assertSame(1, $run['pendingWhileWaiting']);

        // The wait that was pending is cleared with the rest, so nothing
        // fires after the take-down.
        $this->assertSame(0, $run['pendingAfterRemoval']);
        $this->assertSame(0, $run['firedInAll']);
        $this->assertTrue($run['intersectionDisconnected']);
        $this->assertTrue($run['removalsDisconnected']);
        $this->assertSame(0, $run['resizeCalls']);
    }

    public function testAnEngineWithoutTheObserverHearsEveryDisclosureAndDropsThemAll(): void
    {
        $run = $this->perform('theObserverlessEngineHearsEveryDisclosure');

        $this->assertFalse($run['intersectionObserverUsed'], 'the engine has none to use');

        // Both disclosures the frame sits inside are listened to, and nothing
        // that is no disclosure is.
        $this->assertSame(2, $run['listenedAtTheStart']);
        $this->assertSame(0, $run['listenedOnTheBody']);

        // One of them opening measures the frame.
        $this->assertSame(1, $run['pendingAfterAnOpening']);
        $this->assertSame(1, $run['resizeAfterAnOpening']);

        // And the frame going takes every listener off the disclosures that
        // outlive it, without anything having to report first.
        $this->assertSame(0, $run['listenersAfterRemoval']);
        $this->assertTrue($run['removalsDisconnected']);
        $this->assertSame(0, $run['pendingAtTheEnd']);
    }

    public function testAnEngineWatchingNothingHearsTheRemovalFromItsOwnWait(): void
    {
        $run = $this->perform('neitherObserverStillStopsWhenTheFrameGoes');

        $this->assertFalse($run['treeIsWatched'], 'the engine carries nothing that could watch it');
        $this->assertSame(2, $run['listenedAtTheStart']);
        $this->assertSame(1, $run['pendingAfterAnOpening']);

        // Nothing here can hear the removal when it happens, so the wait is
        // still pending and the listeners are still on.
        $this->assertSame(1, $run['pendingAfterRemoval']);
        $this->assertSame(2, $run['listenersAfterRemoval']);

        // The wait asks on its next attempt and takes everything down there,
        // so one attempt runs and the chain stops.
        $this->assertSame(1, $run['firedInAll']);
        $this->assertSame(0, $run['listenersOnceTheWaitRan']);
        $this->assertSame(0, $run['pendingAtTheEnd']);
        $this->assertSame(0, $run['resizeCalls']);
    }

    public function testADisclosureFiringForAFrameThatWentTakesTheWatchDown(): void
    {
        $run = $this->perform('aDisclosureThatFiresForAFrameThatWent');

        $this->assertSame(1, $run['pendingWhileWaiting']);

        // The disclosure fires while a wait is pending, and the watch comes
        // down there rather than at the wait's next attempt.
        $this->assertSame(0, $run['listenersAfterTheDisclosureFired']);
        $this->assertSame(0, $run['pendingAfterTheDisclosureFired']);
    }

    public function testAReportIsStillAWayInWhereNothingWatchesTheTree(): void
    {
        $run = $this->perform('theReportIsStillAWayInWhereNothingWatchesTheTree');

        $this->assertFalse($run['treeIsWatched']);
        $this->assertFalse($run['disconnectedBeforeAnyReport']);

        // A frame that was on screen reports once more that nothing is
        // intersecting, and that report is heard.
        $this->assertTrue($run['disconnectedAfterTheReport']);
    }

    public function testAMeasurementFromAWaitLeavesTheWatchReadyForTheNext(): void
    {
        $run = $this->perform('aMeasurementFromAWaitLeavesTheWatchReady');

        // The measurement comes from the wait rather than from the
        // uncovering, so this is where the flag could be left standing.
        $this->assertSame(1, $run['pendingWhileWaiting']);
        $this->assertSame(1, $run['resizeFromTheWait']);

        // The next uncovering is measured rather than swallowed.
        $this->assertSame(2, $run['resizeAfterTheNextUncovering']);
        $this->assertSame(1, $run['scheduledInAll']);
        $this->assertSame(0, $run['pendingAtTheEnd']);
    }

    public function testAnUncoveringWithTheResizerAttachedMeasuresOnce(): void
    {
        $run = $this->perform('anUncoveringWithTheResizerAttached');

        $this->assertSame(1, $run['resizeCallsAfterOneUncovering']);
        $this->assertSame(0, $run['scheduledAfterOneUncovering'], 'an attached resizer is asked, not waited for');
        $this->assertSame(0, $run['pendingAfterOneUncovering']);
        $this->assertSame(1, $run['resizeCallsAtTheEnd']);
    }

    public function testTheTwentyFiveAttemptsBelongToTheFrame(): void
    {
        $run = $this->perform('theBoundHoldsAcrossManyUncoverings');

        // Ten uncoverings, each given all the time its chain could want: 25
        // measurements between them, not 25 each.
        $this->assertSame(10, $run['uncoverings']);
        $this->assertSame(25, $run['scheduledAcrossThem']);
        $this->assertSame(25, $run['firedAcrossThem']);
        $this->assertSame(0, $run['resizeCallsAcrossThem']);

        // Once they are spent the frame is never waited for again, and an
        // uncovering still measures it as soon as a resizer is there to ask.
        $this->assertSame(0, $run['scheduledOnceTheResizerAttached']);
        $this->assertSame(1, $run['resizeCallsOnceTheResizerAttached']);
        $this->assertTrue($run['watchStillOn']);
    }

    public function testAHiddenFrameTheDocumentStillHoldsKeepsItsWatch(): void
    {
        $run = $this->perform('aHiddenButPresentFrameKeepsItsWatch');

        // The page changes around it twice and it reports nothing
        // intersecting throughout, which is what being hidden looks like.
        $this->assertSame(2, $run['removalsHeardOf']);
        $this->assertFalse($run['intersectionDisconnected']);
        $this->assertFalse($run['removalsDisconnected']);

        // So it is still there to measure the frame when it is uncovered.
        $this->assertSame(1, $run['pendingAfterItIsUncovered']);
        $this->assertSame(1, $run['scheduledAfterItIsUncovered']);
    }

    /**
     * Run one scenario of the harness against the emitted watch.
     *
     * @return array<string, mixed>
     */
    private function perform(string $scenario): array
    {
        // tempnam reserves a name; the watch is written beside it under that
        // name with a .js suffix, which is what makes a report of node's own
        // name a JavaScript file. Both are removed whatever happens below.
        $reserved = tempnam(sys_get_temp_dir(), 'embed-watch-');
        $this->assertIsString($reserved);
        $path = $reserved.'.js';

        $output = [];
        $status = 1;

        try {
            $this->assertNotFalse(file_put_contents($path, $this->watch()));

            exec(
                'node '.escapeshellarg(__DIR__.'/Support/embed-watch-harness.js')
                    .' '.escapeshellarg($path).' '.escapeshellarg($scenario).' 2>&1',
                $output,
                $status
            );
        } finally {
            if (is_file($path)) {
                unlink($path);
            }

            if (is_file($reserved)) {
                unlink($reserved);
            }
        }

        $said = implode("\n", $output);

        $this->assertSame(0, $status, 'the scenario '.$scenario.' did not run: '.$said);

        $result = json_decode($said, true);

        $this->assertIsArray($result, 'the scenario '.$scenario.' reported nothing a parser reads: '.$said);

        return $result;
    }

    /**
     * The watch the box of a page's figures emits, exactly as a page gets it.
     */
    private function watch(): string
    {
        $html = EmbedMarkup::of(
            (new EmbedService(EmbedMarkup::core()))->pageFigures(new PageFiguresBoxParams(self::figures()))
        );

        $this->assertSame(
            1,
            preg_match('@\\n  (function remeasureOnUncover\\(\\) \\{\\n.*?\\n  \\})\\n@s', $html, $matches),
            'the rendered box carries the watch'
        );

        return $matches[1];
    }

    /**
     * A page whose every figure arrived.
     */
    private static function figures(): array
    {
        return [
            'entity_type' => 'article',
            'entity_uuid' => 'f1b0c0de-0000-4000-8000-000000000001',
            'langcode' => 'en',
            'content_health_percent_0_to_100' => 84,
            'open_issue_count' => 3,
            'content_priority' => 300,
            'cited_in_answers_last_30_days' => 12,
            'summary_updated' => 1758240000,
            'tags' => [
                ['key' => 'pricing', 'name' => 'Pricing'],
            ],
        ];
    }
}
