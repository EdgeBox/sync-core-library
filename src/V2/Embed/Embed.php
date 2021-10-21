<?php

namespace EdgeBox\SyncCore\V2\Embed;

use EdgeBox\SyncCore\Helpers\EmbedResult;
use EdgeBox\SyncCore\Interfaces\Embed\IEmbedService;
use EdgeBox\SyncCore\Interfaces\IApplicationInterface;
use EdgeBox\SyncCore\V2\SyncCore;

abstract class Embed
{
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
        $this->url = $this->core->getCloudEmbedUrl().'/'.$embed_id;
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

        $html = '<style>
  #contentSyncEmbed {
    width: 1px;
    min-width: 100%;
    min-height: 200px;
  }
</style>
<iframe id="contentSyncEmbed" src="'.$this->url.'" frameborder="0">
  The page could not be loaded as your browser does not support it.
</iframe>
<script type="text/javascript" src="'.$this->core->getCloudEmbedUrl().'/iframeResizer.js"></script>
<script>
(function() {
  // Avoid "mixed content" error message in case the base url is given as http but the currrent site is loaded via https.
  const getHttpsUrl = (url) => url.replace(/^https?:/, "");
  var listEntitiesUrl = getHttpsUrl("'.$list_entities_url.(str_contains($list_entities_url, '?') ? '&' : '?').'");
  var retrieveEntityUrl = getHttpsUrl("'.$retrieve_entity_url.(str_contains($retrieve_entity_url, '?') ? '&' : '?').'");

  var iframe = undefined;

  iFrameResize({
    //log: true,
    checkOrigin: false,
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
      else {
        throw new Error("Unknown message "+JSON.stringify(message));
      }
    },
  }, "#contentSyncEmbed");
})();
</script>';

        return new EmbedResult(EmbedResult::TYPE_RENDER, $html);
    }
}
