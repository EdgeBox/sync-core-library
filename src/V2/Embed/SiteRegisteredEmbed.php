<?php

namespace EdgeBox\SyncCore\V2\Embed;

use EdgeBox\SyncCore\Interfaces\IApplicationInterface;
use EdgeBox\SyncCore\V2\SyncCore;

// TODO: Library: add interface.
class SiteRegisteredEmbed extends Embed
{
    public function __construct(SyncCore $core)
    {
        parent::__construct(
        $core,
        Embed::SITE_REGISTERED,
            IApplicationInterface::SYNC_CORE_PERMISSIONS_CONFIGURATION
    );
    }

    public function run()
    {
        return $this->render();
    }
}
