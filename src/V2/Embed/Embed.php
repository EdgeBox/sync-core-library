<?php

namespace EdgeBox\SyncCore\V2\Embed;

use EdgeBox\SyncCore\V2\SyncCore;

// TODO: Add interface for constants
abstract class Embed
{
    public const REGISTER_SITE = 'register-site';
    public const SITE_REGISTERED = 'site-registered';
    public const PULL_DASHBOARD = 'pull-dashboard';

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
     * Embed constructor.
     */
    public function __construct(SyncCore $core, string $embed_id, string $permissions)
    {
        $this->core = $core;
        $this->url = $this->core->getCloudEmbedUrl().'/'.$embed_id;

        $this->config = [
        'jwt' => $this->core->createJwt($permissions),
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

        $html = '<style>
  #contentSyncEmbed {
    width: 1px;
    min-width: 100%;
  }
</style>
<iframe id="contentSyncEmbed" src="'.$this->url.'" frameborder="0">
  The page could not be loaded as your browser does not support it.
</iframe>
<script type="text/javascript" src="//'.$this->core->getCloudEmbedUrl().'/iframeResizer.js"></script>
<script>
(function() {
  const iframe = iFrameResize({
    log: true,
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
    onMessage: function({iframe,message}) {
      // Need a fresh access token.
      if(message.type==="reload") {
        window.location.reload();
      }
      else if(message.type==="register-site") {
        window.location.href = "'.$this->core->getApplication()->getEmbedBaseUrl(Embed::REGISTER_SITE).'";
      }
    },
  }, "#contentSyncEmbed");
})();
</script>';

        return new EmbedResult(EmbedResult::TYPE_RENDER, $html);
    }
}
