<?php

namespace EdgeBox\SyncCore\V2\Embed;

use EdgeBox\SyncCore\Helpers\EmbedResult;
use EdgeBox\SyncCore\Interfaces\Embed\IEmbedService;
use EdgeBox\SyncCore\Interfaces\IApplicationInterface;
use EdgeBox\SyncCore\V2\SyncCore;

abstract class Embed
{
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
     * Embed constructor.
     */
    public function __construct(SyncCore $core, string $embed_id, string $permissions)
    {
        $this->core = $core;
        $this->url = $this->core->getCloudEmbedUrl().'/'.str_replace('.', '/', $embed_id);
        $this->permissions = $permissions;

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

    protected function render()
    {
        $options = $this->getOptions();

        if ($this->permissions) {
            $this->config['jwt'] = $this->core->createJwt($this->permissions);
        }

        $application = $this->core->getApplication();
        $list_entities_url = $application->getSiteBaseUrl().$application->getRelativeReferenceForRestCall('[flow.machineName]', IApplicationInterface::REST_ACTION_LIST_ENTITIES);
        $retrieve_entity_url = $application->getSiteBaseUrl().$application->getRelativeReferenceForRestCall('[flow.machineName]', IApplicationInterface::REST_ACTION_RETRIEVE_ENTITY);

        if (!empty($application->getFeatureFlags()['custom_embed_message_handling'])) {
            $process_messages_javascript = $application->getCustomEmbedMessageHandlingJavascript();
        } else {
            $process_messages_javascript = '';
        }

        $size = empty($options['embedSize']) ? 'page' : $options['embedSize'];
        $is_page = 'page' === $size;
        $is_line = 'line' === $size;

        $id = $is_page ? 'contentSyncEmbed' : 'contentSyncEmbed-'.preg_replace('@[^a-z0-9-]@', '-', uniqid('', true));

        $html = '<style>
  #'.$id.' {
    '.($is_page ? 'min-width: 100%; width: 1px;' : 'width: 470px;').'
    '.($is_page ? 'min-height: 200px;' : 'height: 32px; max-height: 40px;').'
    '.($is_line ? 'border-radius: 5px;' : '').'
  }
</style>
<iframe id="'.$id.'" src="'.($is_line ? '' : $this->url).'" frameborder="0" class="content-sync-embed size-'.$size.'" loading="lazy">
  The page could not be loaded as your browser does not support it.
</iframe>
'.(Embed::$iframeResizerAdded ? '' : Embed::$iframeResizerAdded = '<script type="text/javascript" src="'.$this->core->getCloudEmbedUrl().'/iframeResizer.js"></script>').'
<script>
(function() {
  // Avoid "mixed content" error message in case the base url is given as http but the currrent site is loaded via https.
  const getHttpsUrl = (url) => url.replace(/^https?:/, "");
  var listEntitiesUrl = getHttpsUrl("'.$list_entities_url.(false !== strpos($list_entities_url, '?') ? '&' : '?').'");
  var retrieveEntityUrl = getHttpsUrl("'.$retrieve_entity_url.(false !== strpos($retrieve_entity_url, '?') ? '&' : '?').'");

  var iframe = undefined;
  function initIframe() {
    iFrameResize({
      //log: true,
      checkOrigin: false,
      autoResize: '.($is_page ? 'true' : 'false').',
      onInit: function(newIframe) {
        iframe = newIframe;
        iframe.iFrameResizer.sendMessage({
          type: "config",
          config: '.json_encode($this->config).',
        });
        iframe.iFrameResizer.sendMessage({
          type: "options",
          options: '.json_encode($options).',
        });
      },
      onMessage: function onMessage({message}) {
        // Code provided by the parent application to add custom message handling.
        '.$process_messages_javascript.'

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
        else {
          throw new Error("Unknown message "+JSON.stringify(message));
        }
      },
    }, "#'.$id.'");
  }

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
