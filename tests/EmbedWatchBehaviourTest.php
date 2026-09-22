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

        // And everything an uncovering is heard through is down before
        // anything else runs, while the one observer that could hear the
        // frame come back stands for the window, which is all that is left
        // pending.
        $this->assertTrue($run['intersectionDisconnected']);
        $this->assertTrue($run['removalsHeldForTheWindow']);
        $this->assertSame([30000], $run['dueAfterTheRemoval']);
        $this->assertSame(0, $run['toggleListeners']);
        $this->assertSame(0, $run['resizeCalls']);

        // And when the window closes with the frame still gone, that observer
        // goes too: one timer fired in the frame's whole life, the window's.
        $this->assertTrue($run['removalsDisconnected']);
        $this->assertSame(0, $run['pendingTimers']);
        $this->assertSame(1, $run['firedInAll']);
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
        $this->assertSame([30000], $run['dueAfterTheRemoval'], 'a region taken out opens the window too');

        // And the window closes with the frame still gone.
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

    public function testAFrameAndItsParentLeavingInOneChangeIsStillHeardOf(): void
    {
        $run = $this->perform('theFrameAndItsParentBothLeaveInOneChange');

        // One change takes the frame out of its parent and then moves that
        // parent out of the document. Putting a node somewhere takes it out
        // of where it was, so nothing stays listed under a parent it left.
        $this->assertFalse($run['stillListedUnderTheOldParent']);
        $this->assertFalse($run['frameInTheDocument']);
        $this->assertTrue($run['theParentIsOutOfTheDocumentToo']);

        // The record for the frame's own removal names a node that is itself
        // out of the document by the time the records are delivered, and the
        // observer is told of it all the same, because where a node lay is
        // read at the moment it changed.
        $this->assertSame(1, $run['removalsHeardOf']);
        $this->assertTrue($run['intersectionDisconnected']);
        $this->assertSame([30000], $run['dueAfterTheChange']);
    }

    public function testAFrameTakenOutWhileAWaitRunsStopsIt(): void
    {
        $run = $this->perform('removedWhileAWaitRuns');

        // One wait pending, due in the 200 milliseconds the watch waits.
        $this->assertSame([200], $run['dueWhileWaiting']);

        // The wait that was pending is cleared with the rest, so what is left
        // pending is the window and nothing else, and the one timer that ever
        // fires is the window's own: the wait never measures anything.
        $this->assertSame([30000], $run['dueAfterRemoval']);
        $this->assertSame(1, $run['firedInAll']);
        $this->assertSame(0, $run['pendingAtTheEnd']);
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
        // outlive it, without anything having to report first, leaving the
        // window and nothing else.
        $this->assertSame(0, $run['listenersAfterRemoval']);
        $this->assertSame([30000], $run['dueAfterTheRemoval']);
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

    public function testAWatchThatStartsWithTheResizerAttachedWaitsForNothing(): void
    {
        $run = $this->perform('theResizerIsAlreadyAttachedWhenTheWatchStarts');

        // The watch is started where the resizer has already attached, which
        // is the state the page is in when the resizer attaches as it is
        // asked to rather than some time afterwards. Starting the watch
        // schedules nothing by itself.
        $this->assertSame(0, $run['scheduledBeforeAnyUncovering']);

        // And every uncovering measures the frame there and then, so the 25
        // are never touched however often it is uncovered.
        $this->assertSame(1, $run['resizeAfterOneUncovering']);
        $this->assertSame(0, $run['scheduledAfterOneUncovering'], 'an attached resizer is asked, not waited for');
        $this->assertSame(2, $run['resizeAfterTwoUncoverings']);
        $this->assertSame(0, $run['scheduledInAll']);
        $this->assertSame(0, $run['pendingAtTheEnd']);
        $this->assertTrue($run['watchStillOn']);
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

    public function testAFramePutBackInsideTheWindowIsWatchedAgain(): void
    {
        $run = $this->perform('putBackInsideTheWindow');

        // Six of the 25 are spent, then the page takes the frame out: what an
        // uncovering is heard through comes down, and the one observer that
        // could hear the frame come back stands for the window.
        $this->assertSame(6, $run['spentBeforeItWent'], 'one uncovering and a second of waiting');
        $this->assertTrue($run['theWatchIsDown']);
        $this->assertTrue($run['removalsHeldForTheWindow']);
        $this->assertSame([30000], $run['dueAfterTheRemoval']);

        // Put back inside it, the watch is built again - once, and once only
        // however much the page keeps changing afterwards - and the window is
        // cleared rather than left to fire.
        $this->assertTrue($run['frameInTheDocument']);
        $this->assertSame(2, $run['builtAgain'], 'the watch the frame started with, and the one it came back to');
        $this->assertSame(2, $run['builtAfterAFurtherChange']);
        $this->assertSame([], $run['dueOnceItIsBack']);
        $this->assertTrue($run['removalsStillWatching']);

        // And the next uncovering answers as it answered before the frame
        // left: on the count it had already spent rather than on a fresh 25,
        // which is what leaves it able to measure at all - a frame that comes
        // back with the 25 spent is measured when the resizer has attached
        // and waited for no longer, exactly as it would have been.
        $this->assertSame(19, $run['spentAfterItIsBack']);
        $this->assertSame(
            25,
            $run['spentBeforeItWent'] + $run['spentAfterItIsBack'],
            'the 25 belong to the frame, and coming back does not start them over'
        );
        $this->assertSame(0, $run['pendingAtTheEnd']);
    }

    public function testAFrameRemovedASecondTimeGetsAWindowOfItsOwn(): void
    {
        $run = $this->perform('removedPutBackAndRemovedAgain');

        // The first removal opens the window, and the frame coming back
        // inside it closes that window rather than leaving it to fire.
        $this->assertSame([30000], $run['dueAfterTheFirstRemoval']);
        $this->assertSame([], $run['dueOnceItIsBack']);
        $this->assertSame(2, $run['builtOnceItIsBack'], 'the watch the frame started with, and the one it came back to');

        // Taken out again, the frame is given a window of its own: a full 30
        // seconds counted from this removal rather than what was left of the
        // first, because the return cleared the first instead of spending it.
        $this->assertSame([30000], $run['dueAfterTheSecondRemoval']);
        $this->assertTrue($run['removalsHeldForTheSecondWindow']);
        $this->assertSame(2, $run['builtAfterTheSecondRemoval'], 'the frame is gone, so nothing is built for it');

        // Left alone, that window closes exactly as the first one would have:
        // the observer kept across the take-down goes with it and nothing of
        // the watch is left on the page, however many times the frame came and
        // went before.
        $this->assertFalse($run['frameInTheDocument']);
        $this->assertTrue($run['removalsDisconnectedTenMinutesLater']);
        $this->assertSame(0, $run['pendingAtTheEnd']);
        $this->assertSame(0, $run['resizeCalls']);
    }

    public function testAFrameReplacedByAFreshElementWithTheSameIdIsNotAdopted(): void
    {
        $run = $this->perform('theFrameIsReplacedByAFreshOneWithTheSameId');

        // The frame goes and the window opens, as it does for any removal.
        $this->assertSame([30000], $run['dueAfterTheRemoval']);

        // What comes back is a different element carrying the same id, which
        // the document finds and this watch does not: the watch holds the
        // element it started on and looks the id up no second time.
        $this->assertTrue($run['theDocumentFindsTheFreshFrame']);
        $this->assertSame(1, $run['builtForTheFreshFrame'], 'nothing is built for a stranger');
        $this->assertSame(
            [30000],
            $run['dueOnceTheFreshFrameIsThere'],
            'the window is neither cleared nor lengthened by an element that is not the frame'
        );

        // So the window runs out unused and the fresh frame, resizer attached
        // and uncovered, is measured by nothing here. Markup rendered afresh
        // carries this script afresh, and that is what watches it.
        $this->assertTrue($run['removalsDisconnected']);
        $this->assertSame(0, $run['resizeCalls']);
        $this->assertSame(0, $run['pendingAtTheEnd']);
    }

    public function testAFramePutBackOnceTheWindowClosedStartsNoWatch(): void
    {
        $run = $this->perform('putBackAfterTheWindow');

        // The window closed with the frame still gone, so nothing of the
        // watch is left on the page.
        $this->assertTrue($run['removalsDisconnected']);
        $this->assertSame(1, $run['builtBeforeItIsBack']);
        $this->assertSame(1, $run['heardBeforeItIsBack'], 'the removal, and nothing since');

        // The page puts the frame back and nothing hears it: no observer is
        // told, nothing is built, no wait starts, and the only timer of the
        // frame's whole life was the window's own. What measures this frame
        // is the resizer as it attaches, which for a hidden frame is the
        // wrong size and stays it.
        $this->assertTrue($run['frameInTheDocument']);
        $this->assertSame(1, $run['heardOnceItIsBack']);
        $this->assertSame(1, $run['builtOnceItIsBack']);
        $this->assertSame(1, $run['scheduledInAll']);
        $this->assertSame(0, $run['pendingAtTheEnd']);
        $this->assertSame(0, $run['resizeCalls']);
    }

    public function testAFrameNeverPutBackLeavesNothingWhenTheWindowCloses(): void
    {
        $run = $this->perform('removedAndNeverPutBack');

        // Through the window one observer stands and nothing else: no
        // intersection observer, no listener, and one timer, the window's.
        $this->assertTrue($run['removalsWatchingInTheWindow']);
        $this->assertTrue($run['intersectionDisconnected']);
        $this->assertSame(0, $run['listenersInTheWindow']);
        $this->assertSame([30000], $run['dueAfterTheRemoval']);

        // The page changing while the frame is gone does not lengthen it: two
        // thirds of the way through, ten seconds of the same window are left.
        $this->assertSame([10000], $run['dueAfterAChangeWhileItIsGone']);

        // And at its end nothing of the watch is left.
        $this->assertTrue($run['removalsDisconnected']);
        $this->assertSame(1, $run['intersectionsBuilt']);
        $this->assertSame(0, $run['pendingAtTheEnd']);
        $this->assertSame(0, $run['resizeCalls']);
    }

    public function testTheWatchIsBuiltOnceHoweverOftenThePageChanges(): void
    {
        $run = $this->perform('theWatchIsBuiltOnceHoweverOftenThePageChanges');

        // Both disclosures the frame sits inside are listened to, and the
        // removal takes both listeners off.
        $this->assertSame(2, $run['listenedAtTheStart']);
        $this->assertSame(0, $run['listenedWhileItIsGone']);

        // The frame comes back, with another change beside it in the same
        // batch, and the watch is built for it. One batch is one callback
        // here as it is one in a browser, so this much no rebuild could get
        // wrong.
        $this->assertSame(2, $run['listenedOnceItIsBack']);

        // What could is what follows: every later change asks for the watch
        // again while the document holds the frame, and each of those asks
        // has to be a no-op rather than a second listener on every disclosure.
        $this->assertSame(2, $run['listenedAfterMoreChanges']);
        $this->assertTrue($run['removalsStillWatching']);

        // So one opening measures the frame once.
        $this->assertSame(1, $run['resizeOnOneOpening']);
        $this->assertSame(0, $run['pendingAtTheEnd']);
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
