<?php

namespace EdgeBox\SyncCore\V2\Embed;

use EdgeBox\SyncCore\Interfaces\Embed\IEmbedFeature;
use EdgeBox\SyncCore\Interfaces\Embed\IEmbedService;
use EdgeBox\SyncCore\Interfaces\IApplicationInterface;
use EdgeBox\SyncCore\V2\SyncCore;

// TODO: Drupal: Use embed instead of the ConfigurePullDashboard.
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
            IApplicationInterface::SYNC_CORE_PERMISSIONS_CONTENT
    );
    }

    protected function getOptions()
    {
        return $this->options;
    }

    public function run()
    {
        return $this->render();
    }

    /**
     * {@inheritdoc}
     *
     * Returning NULL means we don't want to use the dashboard from the Drupal module but
     * rather provide our own.
     */
    public function getConfig()
    {
        return null;
    }
}
