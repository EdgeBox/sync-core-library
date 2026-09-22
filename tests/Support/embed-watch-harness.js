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
  let mutationsPending = 0;

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

  function advance(ms) {
    const until = now + ms;
    let guard = 0;

    for (;;) {
      guard += 1;
      if (guard > 10000) {
        throw new Error('the watch keeps scheduling without end');
      }

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

    mutationsPending += 1;
  }

  function detach(child) {
    const parent = child.parentNode;

    if (parent) {
      const at = parent.childNodes.indexOf(child);

      if (at !== -1) {
        parent.childNodes.splice(at, 1);
      }

      child.parentNode = null;
    }

    mutationsPending += 1;
  }

  // Records reach an observer once the change the page made has finished, so
  // a scenario makes its changes and then lets this tick.
  function flushMutations() {
    if (mutationsPending === 0) {
      return 0;
    }

    mutationsPending = 0;

    mutationObservers.slice().forEach((observer) => {
      if (observer.watching) {
        observer.deliveries += 1;
        observer.callback([{type: 'childList'}], observer);
      }
    });

    return 1;
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
    intersection: () => (intersectionObservers.length > 0 ? intersectionObservers[0] : null),
    listenerCount: listenerCount,
    makeNode: makeNode,
    mutation: () => (mutationObservers.length > 0 ? mutationObservers[0] : null),
    pending: () => timers.length,
    report: report,
    resizeCalls: () => resizeCalls,
    scheduled: () => scheduled,
    settle: () => {
      mutationsPending = 0;
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
    world.advance(10000);

    return {
      removalsAreWatchedFor: removals !== null,
      observesTheDocumentElement: removals !== null && removals.target === world.documentElement(),
      watchesChildLists: removals !== null && true === removals.options.childList,
      watchesTheWholeSubtree: removals !== null && true === removals.options.subtree,
      intersectionReports: world.intersection() === null ? 0 : world.intersection().reports,
      intersectionDisconnected: world.intersection() !== null && world.intersection().disconnected,
      removalsDisconnected: removals !== null && removals.disconnected,
      pendingTimers: world.pending(),
      firedInAll: world.fired(),
      toggleListeners: world.listenerCount(page.inner, 'toggle') + world.listenerCount(page.outer, 'toggle'),
      resizeCalls: world.resizeCalls(),
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

    const pendingWhileWaiting = world.pending();

    world.detach(page.frame);
    world.flushMutations();

    const pendingAfterRemoval = world.pending();

    world.advance(10000);

    return {
      pendingWhileWaiting: pendingWhileWaiting,
      pendingAfterRemoval: pendingAfterRemoval,
      firedInAll: world.fired(),
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
    world.advance(10000);

    return {
      intersectionObserverUsed: world.intersection() !== null,
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
