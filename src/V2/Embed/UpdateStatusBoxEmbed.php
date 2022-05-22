<?php

namespace EdgeBox\SyncCore\V2\Embed;

use EdgeBox\SyncCore\Interfaces\Embed\IEmbedFeature;
use EdgeBox\SyncCore\Interfaces\Embed\IEmbedService;
use EdgeBox\SyncCore\Interfaces\IApplicationInterface;
use EdgeBox\SyncCore\V2\SyncCore;

class UpdateStatusBoxEmbed extends Embed implements IEmbedFeature
{
    protected $params;

    public function __construct(SyncCore $core, array $params)
    {
        parent::__construct(
            $core,
            IEmbedService::BOX_UPDATE_STATUS,
            !empty($params['configurationAccess'])
                ? IApplicationInterface::SYNC_CORE_PERMISSIONS_CONFIGURATION
                : IApplicationInterface::SYNC_CORE_PERMISSIONS_CONTENT
        );

        $this->params = $params + ['embedSize' => 'line'];
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
