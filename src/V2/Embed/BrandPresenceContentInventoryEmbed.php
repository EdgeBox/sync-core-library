<?php

namespace EdgeBox\SyncCore\V2\Embed;

use EdgeBox\SyncCore\Interfaces\Aim\ActingUser;
use EdgeBox\SyncCore\Interfaces\Embed\IEmbedFeature;
use EdgeBox\SyncCore\Interfaces\Embed\IEmbedService;
use EdgeBox\SyncCore\Interfaces\IApplicationInterface;
use EdgeBox\SyncCore\V2\SyncCore;

class BrandPresenceContentInventoryEmbed extends Embed implements IEmbedFeature
{
    protected $params;

    public function __construct(SyncCore $core, array $params, ?ActingUser $as = null)
    {
        parent::__construct(
            $core,
            IEmbedService::BRAND_PRESENCE_CONTENT_INVENTORY,
            !empty($params['configurationAccess'])
                ? IApplicationInterface::SYNC_CORE_PERMISSIONS_CONFIGURATION
                : IApplicationInterface::SYNC_CORE_PERMISSIONS_CONTENT,
            $as
        );

        $this->params = $params;
    }

    public function run()
    {
        return $this->render();
    }

    protected function getOptions()
    {
        return $this->params;
    }
}
