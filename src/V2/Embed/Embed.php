<?php

namespace EdgeBox\SyncCore\V2\Embed;

use EdgeBox\SyncCore\Helpers\EmbedResult;
use EdgeBox\SyncCore\Interfaces\Embed\IEmbedService;
use EdgeBox\SyncCore\Interfaces\Governance\ActingUser;
use EdgeBox\SyncCore\Interfaces\IApplicationInterface;
use EdgeBox\SyncCore\V2\SyncCore;

abstract class Embed
{
    /**
     * The option a frame's size travels under.
     *
     * @var string
     */
    public const OPTION_SIZE = 'embedSize';

    /**
     * A frame that fills the page it is on and states a minimum height.
     *
     * @var string
     */
    public const SIZE_PAGE = 'page';

    /**
     * A frame of one fixed line, loaded when it is scrolled to.
     *
     * @var string
     */
    public const SIZE_LINE = 'line';

    /**
     * A frame that fills the region it is placed in and is as tall as the
     * document it loads.
     *
     * @var string
     */
    public const SIZE_BOX = 'box';

    public static $iframeResizerAdded = '';
    public static $embed_count = 0;

    /**
     * @var SyncCore
     */
    protected $core;

    /**
     * @var string
     */
    protected $url;

    /**
     * @var array
     */
    protected $config;

    /**
     * @var string
     */
    protected $permissions;

    /**
     * @var null|ActingUser
     */
    protected $actingUser;

    /**
     * Whether the frame is measured again each time it is uncovered.
     *
     * The resizer measures a frame once, when it attaches, and a frame that is
     * hidden then — inside a collapsed `details` element, on a tab that is not
     * selected — measures as a strip of a few pixels and stays one. With this
     * set, the script watches the frame and has the resizer measure it again
     * whenever it becomes visible: an element that goes from `display: none`
     * to shown is uncovered, so opening a `details` element and selecting a tab
     * are covered as well as scrolling it into view. An engine that carries no
     * observer of its own hears one thing instead, a disclosure the frame sits
     * inside opening, and there a frame uncovered any other way keeps the size
     * it was last measured at. The disclosures it listens to are the ones the
     * frame sat inside when the watch was built, so on that engine a frame
     * moved into another disclosure without ever leaving the document is
     * still heard from the ones it left; it is heard from the ones it sits
     * inside now once it does leave and come back, which builds the watch
     * again where the frame then is.
     *
     * Until the resizer has attached, the script asks again every 200
     * milliseconds; the frame is asked 25 times in all, however often it is
     * uncovered, and one such wait runs at a time. Once those are spent the
     * frame is never waited for again, and once the resizer has attached an
     * uncovering measures the frame straight away.
     *
     * Every way a frame leaves the document takes the watch and its listeners
     * down with it, because the watch is told of the change to the page's own
     * tree and asks there whether the document still holds the frame; a wait,
     * a disclosure or a report that runs first only gets there sooner. An
     * engine so old that it watches no change at all hears it from the next
     * wait or disclosure instead.
     *
     * One thing outlives that take-down, and for 30 seconds: what the change
     * to the page's own tree is heard through. It holds the element the watch
     * started on and looks the id up no second time, so what it can hear come
     * back is that same element put back — which is what a widget re-parenting
     * across a task does, and what a re-render that takes the frame out now
     * and appends it again on a later one does. Put back inside those 30
     * seconds, the element is watched again exactly as at the start, with the
     * count of 25 carrying on where it stopped rather than starting over, so
     * an uncovering answers as it answered before the element left: it
     * measures the frame straight away once the resizer has attached, and
     * spends what is left of the 25 while it has not. When the window closes
     * with the frame still gone, that last observer goes too and nothing of
     * the watch is left on the page.
     *
     * One window stands at a time, and it belongs to the removal that opened
     * it. A frame taken out again after a return this observer was called for
     * gets a fresh 30 seconds, because that call cleared the window the return
     * ended. A round trip the page makes inside one change — the frame put
     * back and taken out again before the change is delivered — is one call
     * that finds the frame gone with the window still standing, so it inherits
     * what is left of those 30 seconds rather than opening one of its own.
     *
     * Two frames fall outside the window. One the page puts back after those
     * 30 seconds, and one that is not the same element at all: a rebuilt
     * region that renders a fresh frame carrying the same id replaces the
     * element rather than moving it, and this watch neither finds nor adopts
     * the stranger, inside the window or after it. What measures either is the
     * resizer as it attaches, and for a frame that is hidden at that moment
     * that measurement is the strip of a few pixels described above, which the
     * frame then keeps until something else measures it. Both are residues of
     * two bounds: the window, rather than an observer of every change to the
     * page held for every box for as long as the reader stays; and one element
     * rather than an id the page may hand to another. Markup rendered afresh
     * carries this script afresh, and a watch that adopted a strange element
     * would spend the frame's remaining attempts asking a resizer that belongs
     * to a watch of its own.
     *
     * The embed class decides this, never an option a caller passes: the
     * options travel to the frame, and a frame's behaviour on the site's page
     * is the library's to decide. PageFiguresEmbed sets it, because the box is
     * placed in a site's edit form where it is often hidden when the page
     * loads; every other embed renders the markup it always rendered.
     *
     * @var bool
     */
    protected $remeasureOnUncover = false;

    /**
     * Embed constructor.
     */
    public function __construct(SyncCore $core, string $embed_id, string $permissions, ?ActingUser $as = null)
    {
        $this->core = $core;
        $this->url = $this->core->getCloudEmbedUrl().'/'.str_replace('.', '/', $embed_id);
        $this->permissions = $permissions;
        $this->actingUser = $as;

        $this->config = [
            'syncCoreDomain' => $this->core->getSyncCoreDomain(),
            'baseUrl' => $this->core->getApplication()->getSiteBaseUrl(),
            'featureFlags' => $this->core->getApplication()->getFeatureFlags(),
        ];
    }

    abstract public function run();

    protected function getOptions()
    {
        return [];
    }

    /**
     * A value as JSON that is safe to sit inside a script element.
     *
     * An option may carry prose a person on the site wrote — the name of a
     * tag among them — and this JSON is written into the page between a
     * script element's tags, where the browser reads text, not JSON.
     * A `<` there can take the parser out of the element: `<!--<script>` opens
     * the escaped state, after which the closing tag closes nothing and the
     * rest of the site's page is swallowed as script text.
     *
     * The escapes are the set a content management system of this ecosystem
     * uses for exactly this place, rather than the angle brackets alone: the
     * markup characters have no meaning inside a JSON string, so escaping all
     * of them costs nothing and leaves nothing to argue about at the next
     * place this value is written to.
     *
     * A byte that is no text is replaced by the replacement character, U+FFFD,
     * rather than making the encoding fail: a failure would write nothing
     * where the value goes and leave a script the browser cannot parse, which
     * would stop every embed on the page instead of spoiling one character of
     * one value.
     *
     * @param mixed $value
     *
     * @return string
     */
    protected static function encodeForScript($value)
    {
        return json_encode(
            $value,
            JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_INVALID_UTF8_SUBSTITUTE
        );
    }

    protected function render(?ActingUser $as = null)
    {
        $options = $this->getOptions();

        if ($this->permissions) {
            $acting = $as ?? $this->actingUser;
            $this->config['jwt'] = $acting
                ? $this->core->createJwt($this->permissions, 'jwt-header', $acting->getScopes(), $acting->toUserClaim())
                : $this->core->createJwt($this->permissions);
        }

        $application = $this->core->getApplication();
        $list_entities_url = $application->getSiteBaseUrl().$application->getRelativeReferenceForRestCall('[flow.machineName]', IApplicationInterface::REST_ACTION_LIST_ENTITIES);
        $retrieve_entity_url = $application->getSiteBaseUrl().$application->getRelativeReferenceForRestCall('[flow.machineName]', IApplicationInterface::REST_ACTION_RETRIEVE_ENTITY);

        // A frame is sized in one of three ways. `page` and `box` both take the
        // width of the element they are placed in; `page` claims a minimum
        // height of its own, while `box` states no height at all so the frame
        // is as tall as the document it loads reports and the resizer keeps it
        // there. Every other value takes the geometry of a fixed line; the
        // empty source and the loader that fills it once the reader scrolls to
        // it belong to `line` itself, so any other value loads with the page.
        $size = empty($options[self::OPTION_SIZE]) ? self::SIZE_PAGE : $options[self::OPTION_SIZE];
        $is_page = self::SIZE_PAGE === $size;
        $is_line = self::SIZE_LINE === $size;
        $is_box = self::SIZE_BOX === $size;

        $id = $is_page ? 'contentSyncEmbed' : 'contentSyncEmbed-'.preg_replace('@[^a-z0-9-]@', '-', uniqid('', true));

        $html = '<style>
  #'.$id.' {
    min-height: 32px;
    '.($is_page || $is_box ? 'min-width: 100%; width: 1px;' : 'width: 470px;').'
    '.($is_page ? 'min-height: 200px;' : ($is_box ? '' : 'height: 32px; max-height: 40px;')).'
    '.($is_line ? 'border-radius: 5px;' : '').'
  }
  #'.$id.'.iframe-modal {
    z-index: 1000000000;
    position: fixed;
    left: 0 !important;
    right: 0 !important;
    top: 0 !important;
    bottom: 0 !important;
    height: 100vh !important;
    width: 100vh !important;
  }
</style>
<iframe id="'.$id.'" src="'.($is_line ? '' : $this->url).'" frameborder="0" class="content-sync-embed size-'.$size.'" loading="lazy" allow="fullscreen">
  The page could not be loaded as your browser does not support it.
</iframe>
'.(Embed::$iframeResizerAdded ? '' : Embed::$iframeResizerAdded = '<script type="text/javascript" src="'.$this->core->getCloudEmbedUrl().'/iframeResizer.js"></script>').'
<script>
(function() {
  // Avoid "mixed content" error message in case the base url is given as http but the currrent site is loaded via https.
  const getHttpsUrl = (url) => url.replace(/^https?:/, "");
  var listEntitiesUrl = getHttpsUrl("'.$list_entities_url.(false !== strpos($list_entities_url, '?') ? '&' : '?').'");
  var retrieveEntityUrl = getHttpsUrl("'.$retrieve_entity_url.(false !== strpos($retrieve_entity_url, '?') ? '&' : '?').'");

  var iframe = undefined, iframeParent = undefined;
  function initIframe() {
    if(typeof iFrameResize==="undefined") {
      setTimeout(initIframe,200);
      return;
    }
    iFrameResize({
      //log: true,
      checkOrigin: false,
      autoResize: '.($is_page || $is_box ? 'true' : 'false').',
      onInit: function(newIframe) {
        iframe = newIframe;
        iframeParent = iframe.parentNode;
        iframe.iFrameResizer.sendMessage({
          type: "config",
          config: '.self::encodeForScript($this->config).',
        });
        iframe.iFrameResizer.sendMessage({
          type: "options",
          options: '.self::encodeForScript($options).',
        });
      },
      onMessage: function onMessage({message}) {
        // Code provided by the parent application to add custom message handling.
        if(window.ContentSyncEmbed && Array.isArray(window.ContentSyncEmbed.messageHandlers)) {
          for(var i=0; i<window.ContentSyncEmbed.messageHandlers.length; i++) {
            if(window.ContentSyncEmbed.messageHandlers[i]({ message: message, iframe: iframe, iframeParent: iframeParent })) {
              return;
            }
          }
        }

        // Need a fresh access token.
        if(message.type==="reload") {
          window.location.reload();
        }
        else if(message.type==="register-site") {
          window.location.href = "'.$this->core->getApplication()->getEmbedBaseUrl(IEmbedService::REGISTER_SITE).'";
        }
        else if(message.type==="migrate") {
          window.location.href = "'.$this->core->getApplication()->getEmbedBaseUrl(IEmbedService::MIGRATE).'";
        }
        else if(message.type==="migration-export-pools" || message.type==="migration-export-flows" || message.type==="migration-skip-flows-test" || message.type==="migration-skip-flows-push" || message.type==="migration-skip-flows-pull" || message.type==="migration-switch") {
          var type = message.type.substr(10);
          jQuery(`.migration-form #edit-${type} input`).prop("checked", false);
          if(message.machineNames) {
            for(var i=0; i<message.machineNames.length; i++) {
              var machineName = message.machineNames[i];
              jQuery(`.migration-form #edit-${type} input[value=${machineName}]`).prop("checked", true);
            }
          }
          jQuery(`.migration-form #edit-action input`).prop("checked", false);
          jQuery(`.migration-form #edit-action input[value=${type}]`).prop("checked", true);
          jQuery(`.migration-form`).submit();
        }
        else if(message.type==="save-flow") {
          jQuery(`.json-form #edit-json`).val(JSON.stringify(message.data));
          jQuery(`.json-form #edit-action input`).prop("checked", false);
          jQuery(`.json-form #edit-action input[value=${message.type}]`).prop("checked", true);
          jQuery(`.json-form`).submit();
        }
        else if(message.type==="count-entities") {
          onMessage({
              message: {
              ...message,
              type: "list-entities",
              page: 0,
              itemsPerPage: 0,
            },
          });
        }
        else if(message.type==="list-entities") {
          var {page, itemsPerPage, mode, namespaceMachineName, machineName, search, callbackId, flowMachineName} = message;
          var params = [
            `page=${page}`,
            `itemsPerPage=${itemsPerPage}`,
            `mode=${mode}`,
            `namespaceMachineName=${namespaceMachineName}`,
            `machineName=${machineName}`,
            ...search ? [`search=${search}`] : [],
          ];
          var baseUrl = listEntitiesUrl
            .replace(/\[flow\.machineName\]/g, flowMachineName||"'.IApplicationInterface::FLOW_NONE.'");
          jQuery.ajax({
            url: `${baseUrl}${params.join("&")}`,
            method: "GET",
            headers: {
              "Accept": "application/json",
            },
            success: function(response, status, xhr) {
              iframe.iFrameResizer.sendMessage({
                type: "response",
                callbackId,
                response,
              });
            },
          });
        }
        else if(message.type==="retrieve-entity") {
          var {namespaceMachineName, machineName, sharedEntityId, callbackId, flowMachineName} = message;
          var url = retrieveEntityUrl
            .replace(/\[flow\.machineName\]/g, flowMachineName||"'.IApplicationInterface::FLOW_NONE.'")
            .replace(/\[type\.namespaceMachineName\]/g, namespaceMachineName)
            .replace(/\[type\.machineName\]/g, machineName)
            .replace(/\[entity\.isTranslationRoot\]/g, "true")
            .replace(/\[entity\.language\]/g, "")
            .replace(/\[entity\.individualTranslation\]/g, "true")
            .replace(/\[entity\.uuid\]/g, sharedEntityId)
            .replace(/\[entity\.sharedId\]/g, sharedEntityId);
          jQuery.ajax({
            url,
            method: "GET",
            headers: {
              "Accept": "application/json",
            },
            success: function(response, status, xhr) {
              iframe.iFrameResizer.sendMessage({
                type: "response",
                callbackId,
                response,
              });
            },
          });
        }
        else if(message.type==="scroll-to-top") {
          // Doesn\'t work in IE but that\'s alright.
          window.scrollTo({
            top: 0,
            left: 0,
            behavior: "smooth",
          });
        }
        else if (message.type === "update-query") {
          var query = Object.entries(message.query).map(([key, value]) => `${encodeURIComponent(key)}=${encodeURIComponent(value)}`).join("&");
          window.history.replaceState(message.query, window.document.title, `?${query}`);
        }
        else if (message.type === "modal-open") {
          iframe.className = iframe.className + " iframe-modal";
          document.body.moveBefore && document.body.moveBefore(iframe, null);
        }
        else if (message.type === "modal-close") {
          iframe.className = iframe.className.replace(/iframe-modal/g, "");
          iframeParent.moveBefore && iframeParent.moveBefore(iframe, null);
        }
        else {
          throw new Error("Unknown message "+JSON.stringify(message));
        }
      },
    }, "#'.$id.'");'.($this->remeasureOnUncover ? '
    remeasureOnUncover();' : '').'
  }
'.($this->remeasureOnUncover ? '
  // The resizer measures the frame once, so a frame hidden at that moment is
  // measured again each time it is uncovered.
  function remeasureOnUncover() {
    var element = document.getElementById("'.$id.'");
    if(!element) {
      return;
    }
    var observer = null;
    var removals = null;
    var listeners = [];
    var waiting = false;
    var timer = null;
    var attempts = 0;
    var built = false;
    var grace = null;
    // The third of this watch\'s three numbers, beside the 25 attempts and the
    // 200 milliseconds between them below: how long a frame the page has taken
    // out of the document is given to come back before the watch forgets it.
    var graceMilliseconds = 30000;
    function onOpen(details) {
      function run() {
        if(details.open) {
          uncovered();
        }
      }
      details.addEventListener("toggle", run);
      listeners.push({on: details, run: run});
    }
    // What an uncovering is heard through, and the one call that builds it:
    // an engine carrying an intersection observer is told when the frame
    // becomes visible, and an older one hears a disclosure the frame sits
    // inside opening instead. Building it twice would observe the frame twice
    // and give each disclosure a second listener, and every change to the page
    // asks for it below, so a watch already standing is left as it is.
    function startWatching() {
      if(built) {
        return;
      }
      built = true;
      // An engine with no observer still fires a disclosure\'s own toggle, and
      // a disclosure that opens is what uncovers a frame placed inside it.
      if(typeof IntersectionObserver==="undefined") {
        var parent = element.parentNode;
        while(parent && parent.nodeType===1) {
          if(parent.tagName.toUpperCase()==="DETAILS") {
            onOpen(parent);
          }
          parent = parent.parentNode;
        }
        return;
      }
      observer = new IntersectionObserver(function(entries) {
        for(var i=0; i<entries.length; i++) {
          if(entries[i].isIntersecting) {
            uncovered();
            return;
          }
        }
        // A report where nothing is intersecting is one more way to hear that
        // the frame has gone, and the earliest one for a frame that was on
        // screen when the page let go of it.
        if(!document.contains(element)) {
          frameHasGone();
        }
      });
      observer.observe(element);
    }
    // Everything an uncovering is heard through comes down together, so a
    // frame the document has let go of leaves nothing of itself behind on a
    // page a reader stays on: the wait that is pending, the intersection
    // observer and every listener a disclosure was given. What the changes to
    // the page\'s own tree are heard through is not taken down here, because
    // that is what a frame coming back is heard through too.
    function stopWatching() {
      built = false;
      waiting = false;
      if(timer!==null) {
        clearTimeout(timer);
        timer = null;
      }
      if(observer) {
        observer.disconnect();
        observer = null;
      }
      for(var i=0; i<listeners.length; i++) {
        listeners[i].on.removeEventListener("toggle", listeners[i].run);
      }
      listeners = [];
    }
    // The frame has left the document. Everything an uncovering is heard
    // through comes down at once, and what the changes to the tree are heard
    // through is kept on its own for the window, so that a frame the page puts
    // back inside it is watched again. One window stands at a time, and it
    // belongs to the removal that opened it: a page that keeps changing while
    // the frame is gone does not lengthen it, a removal after a return this
    // callback was called for opens a fresh one because that call cleared the
    // window the return ended, and a round trip made inside one change - the
    // frame put back and taken out again before the change is delivered - is
    // one call that finds the frame gone with the window still standing, so
    // it inherits what is left of it. When a window closes with the frame
    // still gone, that last observer goes too and nothing of the watch is
    // left. An engine watching no change at all has nothing that could hear a
    // frame come back, so there the take-down is the end of it.
    function frameHasGone() {
      stopWatching();
      if(!removals || grace!==null) {
        return;
      }
      grace = setTimeout(function() {
        grace = null;
        if(removals) {
          removals.disconnect();
          removals = null;
        }
      }, graceMilliseconds);
    }
    function remeasure() {
      // A frame the document no longer holds is one a rebuilt form replaced.
      // The membership test is the one every engine that gets here has, and
      // for a frame found by its id it asks what the element would answer.
      if(!document.contains(element)) {
        frameHasGone();
        return;
      }
      // A frame whose resizer is not the one this asks of falls through to
      // the wait, rather than throwing out of the callback that got here.
      if(element.iFrameResizer && typeof element.iFrameResizer.resize==="function") {
        waiting = false;
        element.iFrameResizer.resize();
        return;
      }
      // The 25 belongs to the frame and not to one uncovering of it: the
      // count is kept across every wait, and once it is spent no wait starts
      // again however often the frame is uncovered afterwards. An uncovering
      // after that still measures the frame straight away once the resizer
      // has attached, which is the case above.
      if(attempts<25) {
        attempts++;
        waiting = true;
        timer = setTimeout(function() {
          timer = null;
          remeasure();
        }, 200);
        return;
      }
      waiting = false;
    }
    // Every way in comes through here, so one wait for the resizer runs at a
    // time however often the frame is uncovered, and the wait that is already
    // running is the one that measures it.
    function uncovered() {
      if(!document.contains(element)) {
        frameHasGone();
        return;
      }
      if(waiting) {
        return;
      }
      remeasure();
    }
    // A frame can leave the document without anything reporting for it: an
    // intersection observer queues an entry only where the intersection
    // changed, and a frame hidden since it was first seen is not intersecting
    // before it is taken out and not intersecting afterwards. So the change to
    // the page\'s own tree is what the removal is heard from, on every engine
    // carrying an observer of changes - which is every engine carrying the
    // intersection observer, and the older ones besides. One thing is asked of
    // each change: whether the document still holds the frame. It is asked
    // after the change that raised the question rather than inside it, so a
    // frame taken out of one parent and put into another within one change is
    // still held and keeps its watch, which is what the handler above does
    // when it moves the frame. A frame put back in a later change is not held
    // when the question is asked, so what an uncovering is heard through comes
    // down - and this observer is then the only thing left that could hear the
    // frame come back, which is why it is kept for the window and builds the
    // watch again there. It is this element that is watched for, not the id:
    // the element is looked up once, so a fresh element carrying the same id
    // is a stranger here and markup rendered afresh brings a watch of its own.
    if(typeof MutationObserver!=="undefined") {
      removals = new MutationObserver(function() {
        if(!document.contains(element)) {
          frameHasGone();
          return;
        }
        if(grace!==null) {
          clearTimeout(grace);
          grace = null;
        }
        // A frame the document holds is a frame that should be watched,
        // whether it never left or has just come back, and the call is a
        // no-op for the watch that is already standing.
        startWatching();
      });
      removals.observe(document.documentElement, {childList: true, subtree: true});
    }
    startWatching();
  }
' : '').'
  function onDocumentReady(clb) {
    if (document.readyState === "complete" || document.readyState === "interactive") {
        setTimeout(clb, 1);
    } else {
        document.addEventListener("DOMContentLoaded", clb);
    }
  }

  '.($is_line
        ? 'onDocumentReady(function() {
          var element = jQuery("#'.$id.'");
          var url = "'.$this->url.'";
          var inited = false;
          function checkIfVisible() {
            if(inited) {
              return;
            }
            var elementTop = element.offset().top;
            var windowBottom = jQuery(window).scrollTop() + jQuery(window).innerHeight();
            // Load 200px before the element enters the viewport.
            if(elementTop-200<windowBottom) {
              element.attr("src", url);
              initIframe();
              inited = true;
              jQuery(window).off("scroll", checkIfVisible);
            }
          }
          checkIfVisible();
          jQuery(window).scroll(checkIfVisible);
        });'
        : 'initIframe();').'
})();
</script>';

        return new EmbedResult(EmbedResult::TYPE_RENDER, $html);
    }
}
