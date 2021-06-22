<?php

namespace EdgeBox\SyncCore\V2\Embed;

use EdgeBox\SyncCore\Helpers\EmbedResult;
use EdgeBox\SyncCore\Interfaces\Embed\IEmbedService;
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
        ];
    }

    protected function getOptions()
    {
        return [];
    }

    abstract public function run();

    protected function render()
    {
        $options = $this->getOptions();

        if ($this->permissions) {
            $this->config['jwt'] = $this->permissions ? $this->core->createJwt($this->permissions) : '';
        }

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
  iFrameResize({
    //log: true,
    onInit: function(iframe) {
      iframe.iFrameResizer.sendMessage({
        type: "config",
        config: '.json_encode($this->config).',
      });
      iframe.iFrameResizer.sendMessage({
        type: "options",
        options: '.json_encode($options).',
      });
    },
    onMessage: function({message}) {
      // Need a fresh access token.
      if(message.type==="reload") {
        window.location.reload();
      }
      else if(message.type==="register-site") {
        window.location.href = "'.$this->core->getApplication()->getEmbedBaseUrl(IEmbedService::REGISTER_SITE).'";
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
    },
  }, "#contentSyncEmbed");
})();
</script>';

        return new EmbedResult(EmbedResult::TYPE_RENDER, $html);
    }
}
