// A stubbed page the watch an embed emits is run against.
//
// The watch is JavaScript this library assembles and a browser runs, so what
// it does - and not only which substrings it contains - is checked by running
// it here against stubs of the pieces it reaches for: a document, an
// intersection observer, a mutation observer, disclosures that fire a toggle,
// a resizer that counts what it is asked, and a clock that moves only when a
// scenario moves it. The watch is compiled into a context holding those stubs
// and nothing else, so a reach for any other global is a reference error here
// rather than a silent pass.
//
// One run performs one named scenario and writes its outcome as JSON on
// stdout, which the PHP case asserts on:
//
//   node embed-watch-harness.js <file holding the emitted watch> <scenario>

'use strict';

const fs = require('fs');
const vm = require('vm');

/**
 * A page and the engine pieces the watch can reach, all of them stubbed.
 *
 * engine.intersectionObserver and engine.mutationObserver decide which of the
 * two the context carries, which is how an engine that has neither, one or
 * both of them is put in front of the watch.
 */
function createWorld(engine) {
  let timerSeq = 0;
  let now = 0;
  let timers = [];
  let scheduled = 0;
  let fired = 0;
  let resizeCalls = 0;
  let mutationRecords = [];

  const byId = Object.create(null);
  const mutationObservers = [];
  const intersectionObservers = [];

  // ---- the clock, which only a scenario moves ----------------------------

  function setTimeoutStub(run, ms) {
    timerSeq += 1;
    scheduled += 1;
    timers.push({id: timerSeq, at: now + (ms || 0), run: run});

    return timerSeq;
  }

  function clearTimeoutStub(id) {
    timers = timers.filter((timer) => timer.id !== id);
  }

  // A browser hands an observer the records of a change at the end of the
  // task that made it, so they are delivered before any timer due after that
  // task - including one the delivery itself cleared. Every step of the clock
  // here does the same before it fires anything, so a scenario that makes a
  // change and then lets the clock move gets a browser's order without having
  // to deliver by hand, and advance(0) is the end of the turn alone.
  function advance(ms) {
    const until = now + ms;
    let guard = 0;

    for (;;) {
      guard += 1;
      if (guard > 10000) {
        throw new Error('the watch keeps scheduling without end');
      }

      flushMutations();

      timers.sort((one, other) => one.at - other.at || one.id - other.id);

      if (timers.length === 0 || timers[0].at > until) {
        break;
      }

      const next = timers.shift();
      now = next.at;
      fired += 1;
      next.run();
    }

    now = until;
  }

  // ---- the tree ----------------------------------------------------------

  function makeNode(tagName, id) {
    return {
      tagName: tagName,
      nodeType: 1,
      id: id || '',
      parentNode: null,
      childNodes: [],
      open: false,
      handlers: Object.create(null),
      addEventListener: function (type, run) {
        this.handlers[type] = (this.handlers[type] || []).concat([run]);
      },
      removeEventListener: function (type, run) {
        this.handlers[type] = (this.handlers[type] || []).filter((one) => one !== run);
      },
    };
  }

  function listenerCount(node, type) {
    return (node.handlers[type] || []).length;
  }

  function dispatch(node, type) {
    (node.handlers[type] || []).slice().forEach((run) => run());
  }

  const documentElement = makeNode('HTML');

  const documentStub = {
    nodeType: 9,
    documentElement: documentElement,
    getElementById: function (id) {
      const found = byId[id];

      return found && documentStub.contains(found) ? found : null;
    },
    contains: function (node) {
      let at = node;

      while (at) {
        if (at === documentStub) {
          return true;
        }

        at = at.parentNode;
      }

      return false;
    },
  };

  documentElement.parentNode = documentStub;

  function append(parent, child) {
    child.parentNode = parent;
    parent.childNodes.push(child);

    if (child.id) {
      byId[child.id] = child;
    }

    // The child list that changed is the parent's, so that is the node the
    // record names, which is the node a browser names in it.
    mutationRecords.push({type: 'childList', target: parent});
  }

  function detach(child) {
    const parent = child.parentNode;

    // A node no parent holds is a node nothing can be taken out of, and a
    // browser records nothing for it.
    if (!parent) {
      return;
    }

    const at = parent.childNodes.indexOf(child);

    if (at !== -1) {
      parent.childNodes.splice(at, 1);
    }

    child.parentNode = null;

    mutationRecords.push({type: 'childList', target: parent});
  }

  // Whether a record reaches an observer, which is the whole of what
  // observe(target, options) was asked for: the node whose child list changed
  // is the observed node itself, or lies inside it and the subtree was asked
  // for, and the kind of change is one that was asked for. So an observer
  // watching the wrong node, or asking for the wrong kind of change, hears
  // nothing here, exactly as it hears nothing in a browser.
  //
  // Where a node lies is asked when the records are delivered, which is after
  // the page has finished the change that produced them.
  function reaches(observer, record) {
    if (record.type === 'childList' && observer.options.childList !== true) {
      return false;
    }

    if (record.target === observer.target) {
      return true;
    }

    if (observer.options.subtree !== true) {
      return false;
    }

    let at = record.target.parentNode;

    while (at) {
      if (at === observer.target) {
        return true;
      }

      at = at.parentNode;
    }

    return false;
  }

  // Records reach an observer once the change the page made has finished, so
  // a scenario makes its changes and then lets this tick, either by hand or
  // by moving the clock, which does it first. One observer is handed the
  // records of that batch that reach it and is left alone when none of them
  // does.
  function flushMutations() {
    if (mutationRecords.length === 0) {
      return 0;
    }

    const batch = mutationRecords;
    let delivered = 0;

    mutationRecords = [];

    mutationObservers.slice().forEach((observer) => {
      if (!observer.watching) {
        return;
      }

      const heard = batch.filter((record) => reaches(observer, record));

      if (heard.length === 0) {
        return;
      }

      observer.deliveries += 1;
      delivered += 1;
      observer.callback(heard, observer);
    });

    return delivered;
  }

  // ---- the observers -----------------------------------------------------

  function MutationObserverStub(callback) {
    this.callback = callback;
    this.watching = false;
    this.disconnected = false;
    this.deliveries = 0;
    this.target = null;
    this.options = {};
    mutationObservers.push(this);
  }

  MutationObserverStub.prototype.observe = function (target, options) {
    this.watching = true;
    this.target = target;
    this.options = options || {};
  };

  MutationObserverStub.prototype.disconnect = function () {
    this.watching = false;
    this.disconnected = true;
  };

  function IntersectionObserverStub(callback) {
    this.callback = callback;
    this.watching = false;
    this.disconnected = false;
    this.reports = 0;
    this.target = null;
    intersectionObservers.push(this);
  }

  IntersectionObserverStub.prototype.observe = function (target) {
    this.watching = true;
    this.target = target;
  };

  IntersectionObserverStub.prototype.disconnect = function () {
    this.watching = false;
    this.disconnected = true;
  };

  // An entry is queued only where the intersection changed, so a scenario
  // says what changed and a scenario that says nothing is a frame nothing
  // ever reports for.
  function report(isIntersecting) {
    intersectionObservers.slice().forEach((observer) => {
      if (observer.watching) {
        observer.reports += 1;
        observer.callback([{target: observer.target, isIntersecting: isIntersecting}], observer);
      }
    });
  }

  // ---- the resizer -------------------------------------------------------

  function attachResizer(node) {
    node.iFrameResizer = {
      resize: function () {
        resizeCalls += 1;
      },
    };
  }

  // ---- the page and the run ----------------------------------------------

  function buildPage(id) {
    const body = makeNode('BODY');
    append(documentElement, body);

    const outer = makeNode('DETAILS');
    append(body, outer);

    const inner = makeNode('DETAILS');
    append(outer, inner);

    const frame = makeNode('IFRAME', id);
    append(inner, frame);

    return {body: body, outer: outer, inner: inner, frame: frame};
  }

  const context = {
    document: documentStub,
    setTimeout: setTimeoutStub,
    clearTimeout: clearTimeoutStub,
  };

  if (engine.intersectionObserver) {
    context.IntersectionObserver = IntersectionObserverStub;
  }

  if (engine.mutationObserver) {
    context.MutationObserver = MutationObserverStub;
  }

  vm.createContext(context);

  function start(source) {
    vm.runInContext(source + '\nremeasureOnUncover();\n', context, {filename: 'embed-watch.js'});
  }

  return {
    advance: advance,
    append: append,
    attachResizer: attachResizer,
    buildPage: buildPage,
    detach: detach,
    dispatch: dispatch,
    documentElement: () => documentElement,
    fired: () => fired,
    flushMutations: flushMutations,
    holds: (node) => documentStub.contains(node),
    intersection: () => (intersectionObservers.length > 0 ? intersectionObservers[0] : null),
    intersections: () => intersectionObservers.length,
    listenerCount: listenerCount,
    makeNode: makeNode,
    mutation: () => (mutationObservers.length > 0 ? mutationObservers[0] : null),
    pending: () => timers.length,
    // What is pending, said as the milliseconds each timer is still due in,
    // so that a scenario names which timer it is looking at rather than
    // counting them.
    pendingDueIn: () => timers.map((timer) => timer.at - now).sort((one, other) => one - other),
    report: report,
    resizeCalls: () => resizeCalls,
    scheduled: () => scheduled,
    settle: () => {
      mutationRecords = [];
    },
    start: start,
  };
}

/**
 * The id the watch looks its frame up by, read out of the watch itself.
 */
function frameId(source) {
  const found = /getElementById\("([^"]+)"\)/.exec(source);

  if (!found) {
    throw new Error('the watch does not look its frame up by an id');
  }

  return found[1];
}

/**
 * A page holding the frame inside two disclosures, with the watch running.
 */
function setUp(source, engine) {
  const world = createWorld(engine);
  const page = world.buildPage(frameId(source));

  // The page was built before the watch started, so none of building it is a
  // change the watch could hear about.
  world.settle();
  world.start(source);

  return {world: world, page: page};
}

const bothObservers = {intersectionObserver: true, mutationObserver: true};

const scenarios = {
  /**
   * Uncovering a frame four times over before the resizer attaches starts one
   * bounded chain of measurements, not four.
   */
  fourUncoveringsBeforeTheResizerAttaches: function (source) {
    const world = setUp(source, bothObservers).world;

    world.report(true);
    world.report(true);
    world.report(true);
    world.report(true);

    const scheduledAfterFour = world.scheduled();
    const pendingAfterFour = world.pending();

    world.advance(10000);

    return {
      scheduledAfterFourUncoverings: scheduledAfterFour,
      pendingAfterFourUncoverings: pendingAfterFour,
      scheduledInAll: world.scheduled(),
      firedInAll: world.fired(),
      pendingAtTheEnd: world.pending(),
      resizeCalls: world.resizeCalls(),
      watchStillOn: !world.intersection().disconnected,
    };
  },

  /**
   * A frame hidden from the moment it was first seen is taken out of the
   * document, and nothing ever reports an intersection for it.
   */
  removedWhileHiddenFromTheStart: function (source) {
    const ready = setUp(source, bothObservers);
    const world = ready.world;
    const page = ready.page;
    const removals = world.mutation();

    world.detach(page.frame);
    world.flushMutations();

    const intersectionDisconnected = world.intersection() !== null && world.intersection().disconnected;
    const removalsHeldForTheWindow = removals !== null && !removals.disconnected;
    const dueAfterTheRemoval = world.pendingDueIn();

    world.advance(40000);

    return {
      removalsAreWatchedFor: removals !== null,
      intersectionReports: world.intersection() === null ? 0 : world.intersection().reports,
      intersectionDisconnected: intersectionDisconnected,
      removalsHeldForTheWindow: removalsHeldForTheWindow,
      dueAfterTheRemoval: dueAfterTheRemoval,
      removalsDisconnected: removals !== null && removals.disconnected,
      pendingTimers: world.pending(),
      firedInAll: world.fired(),
      toggleListeners: world.listenerCount(page.inner, 'toggle') + world.listenerCount(page.outer, 'toggle'),
      resizeCalls: world.resizeCalls(),
    };
  },

  /**
   * A frame that leaves the document because an ancestor did, which is what a
   * rebuilt form does to it: the frame itself still has a parent throughout.
   */
  removedWithItsContainer: function (source) {
    const ready = setUp(source, bothObservers);
    const world = ready.world;
    const page = ready.page;
    const removals = world.mutation();

    world.detach(page.outer);
    world.flushMutations();

    const dueAfterTheRemoval = world.pendingDueIn();

    world.advance(40000);

    return {
      frameStillHasAParent: page.frame.parentNode !== null,
      frameStillInTheDocument: world.holds(page.frame),
      dueAfterTheRemoval: dueAfterTheRemoval,
      intersectionDisconnected: world.intersection().disconnected,
      removalsDisconnected: removals !== null && removals.disconnected,
      toggleListeners: world.listenerCount(page.inner, 'toggle') + world.listenerCount(page.outer, 'toggle'),
      pendingTimers: world.pending(),
      resizeCalls: world.resizeCalls(),
    };
  },

  /**
   * A frame taken out and put back inside one change to the page, which is
   * what moving it from one parent to another is.
   */
  movedWithinOneChange: function (source) {
    const ready = setUp(source, bothObservers);
    const world = ready.world;
    const page = ready.page;
    const removals = world.mutation();
    const elsewhere = world.makeNode('DIV');

    world.append(page.body, elsewhere);
    world.detach(page.frame);
    world.append(elsewhere, page.frame);
    world.flushMutations();

    const frameInTheDocument = world.holds(page.frame);
    const intersectionDisconnected = world.intersection().disconnected;
    const removalsDisconnected = removals !== null && removals.disconnected;
    const removalsHeardOf = removals === null ? 0 : removals.deliveries;

    world.report(true);

    return {
      frameInTheDocument: frameInTheDocument,
      intersectionDisconnected: intersectionDisconnected,
      removalsDisconnected: removalsDisconnected,
      removalsHeardOf: removalsHeardOf,
      pendingAfterItIsUncovered: world.pending(),
    };
  },

  /**
   * A frame taken out of the document while a wait for its resizer is running.
   */
  removedWhileAWaitRuns: function (source) {
    const ready = setUp(source, bothObservers);
    const world = ready.world;
    const page = ready.page;
    const removals = world.mutation();

    world.report(true);

    const dueWhileWaiting = world.pendingDueIn();

    world.detach(page.frame);

    // Nothing delivers the record by hand here: the turn the removal was made
    // in ends, which is what puts the record in front of the observer before
    // the wait's own timer is due.
    world.advance(0);

    const dueAfterRemoval = world.pendingDueIn();

    world.advance(40000);

    return {
      dueWhileWaiting: dueWhileWaiting,
      dueAfterRemoval: dueAfterRemoval,
      firedInAll: world.fired(),
      pendingAtTheEnd: world.pending(),
      intersectionDisconnected: world.intersection().disconnected,
      removalsDisconnected: removals !== null && removals.disconnected,
      resizeCalls: world.resizeCalls(),
    };
  },

  /**
   * An engine carrying no intersection observer hears the disclosures the
   * frame sits inside, measures on an opening, and drops every listener once
   * the frame has gone.
   */
  theObserverlessEngineHearsEveryDisclosure: function (source) {
    const ready = setUp(source, {intersectionObserver: false, mutationObserver: true});
    const world = ready.world;
    const page = ready.page;
    const removals = world.mutation();

    const listenedAtTheStart = world.listenerCount(page.inner, 'toggle') + world.listenerCount(page.outer, 'toggle');

    page.inner.open = true;
    world.dispatch(page.inner, 'toggle');

    const pendingAfterAnOpening = world.pending();

    world.attachResizer(page.frame);
    world.advance(200);

    const resizeAfterAnOpening = world.resizeCalls();

    world.detach(page.frame);
    world.flushMutations();

    const dueAfterTheRemoval = world.pendingDueIn();

    world.advance(40000);

    return {
      intersectionObserverUsed: world.intersection() !== null,
      dueAfterTheRemoval: dueAfterTheRemoval,
      listenedAtTheStart: listenedAtTheStart,
      listenedOnTheBody: world.listenerCount(page.body, 'toggle'),
      pendingAfterAnOpening: pendingAfterAnOpening,
      resizeAfterAnOpening: resizeAfterAnOpening,
      listenersAfterRemoval: world.listenerCount(page.inner, 'toggle') + world.listenerCount(page.outer, 'toggle'),
      removalsDisconnected: removals !== null && removals.disconnected,
      pendingAtTheEnd: world.pending(),
    };
  },

  /**
   * An engine carrying neither observer hears a removal from the wait it has
   * running, and a disclosure that fires hears it too.
   */
  neitherObserverStillStopsWhenTheFrameGoes: function (source) {
    const ready = setUp(source, {intersectionObserver: false, mutationObserver: false});
    const world = ready.world;
    const page = ready.page;

    const listenedAtTheStart = world.listenerCount(page.inner, 'toggle') + world.listenerCount(page.outer, 'toggle');

    page.inner.open = true;
    world.dispatch(page.inner, 'toggle');

    const pendingAfterAnOpening = world.pending();

    world.detach(page.frame);
    world.flushMutations();

    // Nothing watches the tree here, so the removal is still unheard.
    const pendingAfterRemoval = world.pending();
    const listenersAfterRemoval = world.listenerCount(page.inner, 'toggle') + world.listenerCount(page.outer, 'toggle');

    world.advance(10000);

    return {
      treeIsWatched: world.mutation() !== null,
      listenedAtTheStart: listenedAtTheStart,
      pendingAfterAnOpening: pendingAfterAnOpening,
      pendingAfterRemoval: pendingAfterRemoval,
      listenersAfterRemoval: listenersAfterRemoval,
      firedInAll: world.fired(),
      listenersOnceTheWaitRan: world.listenerCount(page.inner, 'toggle') + world.listenerCount(page.outer, 'toggle'),
      pendingAtTheEnd: world.pending(),
      resizeCalls: world.resizeCalls(),
    };
  },

  /**
   * A disclosure that fires for a frame that has gone takes the watch down
   * there and then, rather than leaving it to the wait that is pending.
   */
  aDisclosureThatFiresForAFrameThatWent: function (source) {
    const ready = setUp(source, {intersectionObserver: false, mutationObserver: false});
    const world = ready.world;
    const page = ready.page;

    page.inner.open = true;
    world.dispatch(page.inner, 'toggle');

    const pendingWhileWaiting = world.pending();

    world.detach(page.frame);

    page.outer.open = true;
    world.dispatch(page.outer, 'toggle');

    return {
      pendingWhileWaiting: pendingWhileWaiting,
      listenersAfterTheDisclosureFired:
        world.listenerCount(page.inner, 'toggle') + world.listenerCount(page.outer, 'toggle'),
      pendingAfterTheDisclosureFired: world.pending(),
    };
  },

  /**
   * Where nothing watches the tree, a report that nothing is intersecting is
   * still a way to hear that the frame has gone.
   */
  theReportIsStillAWayInWhereNothingWatchesTheTree: function (source) {
    const ready = setUp(source, {intersectionObserver: true, mutationObserver: false});
    const world = ready.world;
    const page = ready.page;

    world.detach(page.frame);
    world.flushMutations();

    const disconnectedBeforeAnyReport = world.intersection().disconnected;

    world.report(false);

    return {
      treeIsWatched: world.mutation() !== null,
      disconnectedBeforeAnyReport: disconnectedBeforeAnyReport,
      disconnectedAfterTheReport: world.intersection().disconnected,
    };
  },

  /**
   * A measurement the wait made leaves the watch ready for the next
   * uncovering rather than holding the flag that swallows it.
   */
  aMeasurementFromAWaitLeavesTheWatchReady: function (source) {
    const ready = setUp(source, bothObservers);
    const world = ready.world;
    const page = ready.page;

    world.report(true);

    const pendingWhileWaiting = world.pending();

    world.attachResizer(page.frame);
    world.advance(200);

    const resizeFromTheWait = world.resizeCalls();

    world.report(true);

    return {
      pendingWhileWaiting: pendingWhileWaiting,
      resizeFromTheWait: resizeFromTheWait,
      resizeAfterTheNextUncovering: world.resizeCalls(),
      scheduledInAll: world.scheduled(),
      pendingAtTheEnd: world.pending(),
    };
  },

  /**
   * An uncovering once the resizer has attached measures the frame straight
   * away, exactly once, and waits for nothing.
   */
  anUncoveringWithTheResizerAttached: function (source) {
    const ready = setUp(source, bothObservers);
    const world = ready.world;
    const page = ready.page;

    world.attachResizer(page.frame);
    world.report(true);

    const resizeCallsAfterOne = world.resizeCalls();
    const scheduledAfterOne = world.scheduled();
    const pendingAfterOne = world.pending();

    world.advance(10000);

    return {
      resizeCallsAfterOneUncovering: resizeCallsAfterOne,
      scheduledAfterOneUncovering: scheduledAfterOne,
      pendingAfterOneUncovering: pendingAfterOne,
      resizeCallsAtTheEnd: world.resizeCalls(),
    };
  },

  /**
   * The watch starts where the resizer has already attached, which is the
   * state the page is in when the resizer attaches as it is asked to rather
   * than some time afterwards: every uncovering measures the frame there and
   * then and the 25 are never touched.
   */
  theResizerIsAlreadyAttachedWhenTheWatchStarts: function (source) {
    const world = createWorld(bothObservers);
    const page = world.buildPage(frameId(source));

    world.attachResizer(page.frame);

    // The page was built and the resizer attached before the watch started,
    // so none of it is a change the watch could hear about.
    world.settle();
    world.start(source);

    const scheduledBeforeAnyUncovering = world.scheduled();

    world.report(true);

    const resizeAfterOneUncovering = world.resizeCalls();
    const scheduledAfterOneUncovering = world.scheduled();

    world.report(true);
    world.advance(10000);

    return {
      scheduledBeforeAnyUncovering: scheduledBeforeAnyUncovering,
      resizeAfterOneUncovering: resizeAfterOneUncovering,
      scheduledAfterOneUncovering: scheduledAfterOneUncovering,
      resizeAfterTwoUncoverings: world.resizeCalls(),
      scheduledInAll: world.scheduled(),
      pendingAtTheEnd: world.pending(),
      watchStillOn: !world.intersection().disconnected,
    };
  },

  /**
   * The 25 attempts are the frame's: ten uncoverings, each given all the time
   * the chain could want, spend 25 between them and not 25 each.
   */
  theBoundHoldsAcrossManyUncoverings: function (source) {
    const ready = setUp(source, bothObservers);
    const world = ready.world;
    const page = ready.page;

    for (let round = 0; round < 10; round += 1) {
      world.report(true);
      world.advance(10000);
    }

    const scheduledAcrossThem = world.scheduled();
    const firedAcrossThem = world.fired();
    const resizeCallsAcrossThem = world.resizeCalls();

    world.attachResizer(page.frame);
    world.report(true);
    world.advance(10000);

    return {
      uncoverings: 10,
      scheduledAcrossThem: scheduledAcrossThem,
      firedAcrossThem: firedAcrossThem,
      resizeCallsAcrossThem: resizeCallsAcrossThem,
      scheduledOnceTheResizerAttached: world.scheduled() - scheduledAcrossThem,
      resizeCallsOnceTheResizerAttached: world.resizeCalls(),
      watchStillOn: !world.intersection().disconnected,
    };
  },

  /**
   * A frame the page takes out and puts back in a later change, inside the
   * window: the watch is built again exactly as at the start, and the count
   * of 25 carries on where it stopped rather than starting over.
   */
  putBackInsideTheWindow: function (source) {
    const ready = setUp(source, bothObservers);
    const world = ready.world;
    const page = ready.page;
    const removals = world.mutation();

    // Some of the 25 are spent before the frame goes.
    world.report(true);
    world.advance(1000);

    const spentBeforeItWent = world.scheduled();

    world.detach(page.frame);
    world.flushMutations();

    const theWatchIsDown = world.intersection().disconnected;
    const removalsHeldForTheWindow = !removals.disconnected;
    const dueAfterTheRemoval = world.pendingDueIn();

    // The window's own timer is a timer like any other, so what the frame
    // spends once it is back is counted from here.
    const scheduledWithTheWindowOpen = world.scheduled();

    // Put back well inside the window, in a change of its own.
    world.advance(10000);
    world.append(page.inner, page.frame);
    world.flushMutations();

    const builtAgain = world.intersections();
    const dueOnceItIsBack = world.pendingDueIn();

    // And the page keeps changing with the frame back where it belongs,
    // which builds nothing further.
    world.append(page.body, world.makeNode('DIV'));
    world.flushMutations();

    // Uncovered again with no resizer to answer: the chain carries on from
    // the count the frame already spent.
    world.report(true);
    world.advance(40000);

    return {
      spentBeforeItWent: spentBeforeItWent,
      theWatchIsDown: theWatchIsDown,
      removalsHeldForTheWindow: removalsHeldForTheWindow,
      dueAfterTheRemoval: dueAfterTheRemoval,
      builtAgain: builtAgain,
      builtAfterAFurtherChange: world.intersections(),
      dueOnceItIsBack: dueOnceItIsBack,
      frameInTheDocument: world.holds(page.frame),
      spentAfterItIsBack: world.scheduled() - scheduledWithTheWindowOpen,
      removalsStillWatching: !removals.disconnected,
      pendingAtTheEnd: world.pending(),
    };
  },

  /**
   * A frame put back once the window has closed: nothing hears it, nothing
   * is built, and the watch left nothing of itself behind.
   */
  putBackAfterTheWindow: function (source) {
    const ready = setUp(source, bothObservers);
    const world = ready.world;
    const page = ready.page;
    const removals = world.mutation();

    world.detach(page.frame);
    world.flushMutations();

    // The window closes with the frame still gone.
    world.advance(40000);

    const removalsDisconnected = removals.disconnected;
    const builtBeforeItIsBack = world.intersections();
    const heardBeforeItIsBack = removals.deliveries;

    world.append(page.inner, page.frame);
    world.flushMutations();

    world.report(true);
    world.advance(40000);

    return {
      removalsDisconnected: removalsDisconnected,
      builtBeforeItIsBack: builtBeforeItIsBack,
      heardBeforeItIsBack: heardBeforeItIsBack,
      frameInTheDocument: world.holds(page.frame),
      builtOnceItIsBack: world.intersections(),
      heardOnceItIsBack: removals.deliveries,
      scheduledInAll: world.scheduled(),
      pendingAtTheEnd: world.pending(),
      resizeCalls: world.resizeCalls(),
    };
  },

  /**
   * A frame taken out and never put back: through the window one observer
   * stands and nothing else, the page changing meanwhile does not lengthen
   * it, and at its end nothing of the watch is left.
   */
  removedAndNeverPutBack: function (source) {
    const ready = setUp(source, bothObservers);
    const world = ready.world;
    const page = ready.page;
    const removals = world.mutation();

    world.report(true);
    world.detach(page.frame);
    world.flushMutations();

    const removalsWatchingInTheWindow = !removals.disconnected;
    const intersectionDisconnected = world.intersection().disconnected;
    const dueAfterTheRemoval = world.pendingDueIn();
    const listenersInTheWindow =
      world.listenerCount(page.inner, 'toggle') + world.listenerCount(page.outer, 'toggle');

    // Two thirds of the way through it, the page changes again.
    world.advance(20000);
    world.append(page.body, world.makeNode('DIV'));
    world.flushMutations();

    const dueAfterAChangeWhileItIsGone = world.pendingDueIn();

    world.advance(40000);

    return {
      removalsWatchingInTheWindow: removalsWatchingInTheWindow,
      intersectionDisconnected: intersectionDisconnected,
      dueAfterTheRemoval: dueAfterTheRemoval,
      listenersInTheWindow: listenersInTheWindow,
      dueAfterAChangeWhileItIsGone: dueAfterAChangeWhileItIsGone,
      removalsDisconnected: removals.disconnected,
      intersectionsBuilt: world.intersections(),
      pendingAtTheEnd: world.pending(),
      resizeCalls: world.resizeCalls(),
    };
  },

  /**
   * The frame comes back in a batch that changed the page in more than one
   * place, and the page keeps changing afterwards: the watch is built once,
   * and each disclosure the frame sits inside is listened to once.
   */
  theRebuildIsIdempotentUnderTwoChangesInOneBatch: function (source) {
    const ready = setUp(source, {intersectionObserver: false, mutationObserver: true});
    const world = ready.world;
    const page = ready.page;
    const removals = world.mutation();

    const listenedAtTheStart =
      world.listenerCount(page.inner, 'toggle') + world.listenerCount(page.outer, 'toggle');

    world.detach(page.frame);
    world.flushMutations();

    const listenedWhileItIsGone =
      world.listenerCount(page.inner, 'toggle') + world.listenerCount(page.outer, 'toggle');

    // One batch, two changes: the frame is put back and something else is
    // added beside it.
    world.append(page.inner, page.frame);
    world.append(page.body, world.makeNode('DIV'));
    world.flushMutations();

    const listenedOnceItIsBack =
      world.listenerCount(page.inner, 'toggle') + world.listenerCount(page.outer, 'toggle');

    // And the page keeps changing with the frame where it belongs.
    world.append(page.body, world.makeNode('DIV'));
    world.flushMutations();
    world.append(page.body, world.makeNode('DIV'));
    world.flushMutations();

    // One opening, one measurement: a disclosure listened to twice would
    // measure twice.
    world.attachResizer(page.frame);
    page.inner.open = true;
    world.dispatch(page.inner, 'toggle');

    return {
      listenedAtTheStart: listenedAtTheStart,
      listenedWhileItIsGone: listenedWhileItIsGone,
      listenedOnceItIsBack: listenedOnceItIsBack,
      listenedAfterMoreChanges:
        world.listenerCount(page.inner, 'toggle') + world.listenerCount(page.outer, 'toggle'),
      removalsStillWatching: !removals.disconnected,
      resizeOnOneOpening: world.resizeCalls(),
      pendingAtTheEnd: world.pending(),
    };
  },

  /**
   * A frame the document still holds keeps its watch, however hidden it is
   * and however much the page changes around it.
   */
  aHiddenButPresentFrameKeepsItsWatch: function (source) {
    const ready = setUp(source, bothObservers);
    const world = ready.world;
    const page = ready.page;
    const removals = world.mutation();

    world.report(false);

    world.append(page.body, world.makeNode('DIV'));
    world.flushMutations();

    world.append(page.body, world.makeNode('DIV'));
    world.flushMutations();

    world.report(false);

    const intersectionDisconnected = world.intersection().disconnected;
    const removalsDisconnected = removals !== null && removals.disconnected;
    const removalsHeardOf = removals === null ? 0 : removals.deliveries;

    world.report(true);

    return {
      intersectionDisconnected: intersectionDisconnected,
      removalsDisconnected: removalsDisconnected,
      removalsHeardOf: removalsHeardOf,
      pendingAfterItIsUncovered: world.pending(),
      scheduledAfterItIsUncovered: world.scheduled(),
    };
  },
};

const watchPath = process.argv[2];
const scenarioName = process.argv[3];

if (!watchPath || !scenarioName) {
  process.stderr.write('usage: node embed-watch-harness.js <file holding the emitted watch> <scenario>\n');
  process.exit(1);
}

if (!Object.prototype.hasOwnProperty.call(scenarios, scenarioName)) {
  process.stderr.write('no scenario is named ' + scenarioName + '\n');
  process.exit(1);
}

process.stdout.write(JSON.stringify(scenarios[scenarioName](fs.readFileSync(watchPath, 'utf8'))) + '\n');
