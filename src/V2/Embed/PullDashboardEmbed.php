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
        parent::__construct(
            $core,
            IEmbedService::PULL_DASHBOARD,
            empty($params['configurationAccess']) ? IApplicationInterface::SYNC_CORE_PERMISSIONS_CONTENT : IApplicationInterface::SYNC_CORE_PERMISSIONS_CONFIGURATION,
        );

        $this->options = $params;
        $this->options['imagePreviewJwt'] = $this->core->createJwt('file-download', 'jwt-param');
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
