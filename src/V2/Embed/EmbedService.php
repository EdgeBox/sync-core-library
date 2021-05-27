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

    /**
     * {@inheritDoc}
     */
    public function registerSite(?array $params)
    {
        return new RegisterSiteEmbed($this->core, $params);
    }

    /**
     * {@inheritDoc}
     */
    public function siteRegistered(?array $params)
    {
        return new SiteRegisteredEmbed($this->core);
    }

    /**
     * {@inheritDoc}
     */
    public function pullDashboard(?array $params)
    {
        return new PullDashboardEmbed($this->core, $params);
    }

    /**
     * {@inheritDoc}
     */
    public function entityStatus(array $params)
    {
        return new EntityStatus($this->core, $params);
    }
}
