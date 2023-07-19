<?php

namespace EdgeBox\SyncCore\V2\Embed;

use EdgeBox\SyncCore\Interfaces\Embed\IEmbedFeature;
use EdgeBox\SyncCore\Interfaces\Embed\IEmbedService;
use EdgeBox\SyncCore\Interfaces\IApplicationInterface;
use EdgeBox\SyncCore\V2\SyncCore;

class PullDashboardEmbed extends Embed implements IEmbedFeature
{
    /**
     * @var array
     */
    protected $options;

    public function __construct(SyncCore $core, $params)
    {
        $this->options = $params;

        parent::__construct(
            $core,
            IEmbedService::PULL_DASHBOARD,
            empty($params['configurationAccess']) ? IApplicationInterface::SYNC_CORE_PERMISSIONS_CONTENT : IApplicationInterface::SYNC_CORE_PERMISSIONS_CONFIGURATION,
        );
    }

    public function run()
    {
        return $this->render();
    }

    protected function getOptions()
    {
        return $this->options;
    }
}
