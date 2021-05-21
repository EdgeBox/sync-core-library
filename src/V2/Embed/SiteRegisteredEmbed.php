<?php

namespace EdgeBox\SyncCore\V2\Embed;

use EdgeBox\SyncCore\Interfaces\Embed\IEmbedFeature;
use EdgeBox\SyncCore\Interfaces\Embed\IEmbedService;
use EdgeBox\SyncCore\Interfaces\IApplicationInterface;
use EdgeBox\SyncCore\V2\SyncCore;

class SiteRegisteredEmbed extends Embed implements IEmbedFeature
{
    public function __construct(SyncCore $core)
    {
        parent::__construct(
        $core,
            IEmbedService::SITE_REGISTERED,
            IApplicationInterface::SYNC_CORE_PERMISSIONS_CONFIGURATION
    );
    }

    public function run()
    {
        return $this->render();
    }
}
