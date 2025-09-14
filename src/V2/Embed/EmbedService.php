<?php

namespace EdgeBox\SyncCore\V2\Embed;

use EdgeBox\SyncCore\Interfaces\Embed\IEmbedService;
use EdgeBox\SyncCore\V2\SyncCore;

class EmbedService implements IEmbedService
{
    /**
     * @var SyncCore
     */
    protected $core;

    /**
     * EmbedService constructor.
     */
    public function __construct(SyncCore $core)
    {
        $this->core = $core;
    }

    public function registerSite(?array $params)
    {
        return new RegisterSiteEmbed($this->core, $params);
    }

    public function siteRegistered(?array $params)
    {
        return new SiteRegisteredEmbed($this->core, $params);
    }

    public function siteSettings(?array $params)
    {
        return new SiteSettingsEmbed($this->core, $params);
    }

    public function pullDashboard(?array $params)
    {
        return new PullDashboardEmbed($this->core, $params);
    }

    public function entityStatus(array $params)
    {
        return new EntityStatusEmbed($this->core, $params);
    }

    public function updateStatusBox(array $params)
    {
        return new UpdateStatusBoxEmbed($this->core, $params);
    }

    public function migrate(array $params)
    {
        return new MigrateEmbed($this->core, $params);
    }

    public function flowForm(array $params)
    {
        return new FlowFormEmbed($this->core, $params);
    }

    public function syndicationDashboard(array $params)
    {
        return new SyndicationDashboardEmbed($this->core, $params);
    }
}
