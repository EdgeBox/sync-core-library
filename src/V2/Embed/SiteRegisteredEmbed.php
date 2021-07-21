<?php

namespace EdgeBox\SyncCore\V2\Embed;

use EdgeBox\SyncCore\Interfaces\Embed\IEmbedFeature;
use EdgeBox\SyncCore\Interfaces\Embed\IEmbedService;
use EdgeBox\SyncCore\Interfaces\IApplicationInterface;
use EdgeBox\SyncCore\V2\SyncCore;

class SiteRegisteredEmbed extends Embed implements IEmbedFeature
{
    protected $params;

    public function __construct(SyncCore $core, ?array $params)
    {
        parent::__construct(
            $core,
            IEmbedService::SITE_REGISTERED,
            IApplicationInterface::SYNC_CORE_PERMISSIONS_CONFIGURATION
        );

        $this->params = $params;
    }

    public function run()
    {
        return $this->render();
    }

    protected function getOptions()
    {
        return $this->params ?? [];
    }
}
