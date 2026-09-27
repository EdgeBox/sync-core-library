<?php

namespace EdgeBox\SyncCore\V2\Embed;

use EdgeBox\SyncCore\Interfaces\Embed\IEmbedFeature;
use EdgeBox\SyncCore\Interfaces\Embed\IEmbedService;
use EdgeBox\SyncCore\Interfaces\Governance\ActingUser;
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

    /**
     * @return IEmbedFeature
     */
    public function optimize(array $params)
    {
        return new OptimizeEmbed($this->core, $params);
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

    public function governanceContentInventory(array $params, ?ActingUser $as = null)
    {
        return new GovernanceContentInventoryEmbed($this->core, $params, $as);
    }

    /**
     * The issue screen, for a site to host beside its own content.
     *
     * The frame is signed with the site's own credentials, so it shows that
     * site's issues and no other site's. The acting person's scopes decide what
     * they may do on a row: a person carrying issue:own:write gets the status
     * control and the two verdicts, and anyone else reads the same rows with
     * the status as text. Sync Core refuses a write for a token without the
     * scope either way.
     *
     * Declared on this class and not on IEmbedService, so a class that
     * implements IEmbedService stays compatible. SyncCore::getEmbedService()
     * returns an instance of this class; code that holds an ISyncCore checks
     * the service it gets with instanceof EmbedService before calling this.
     *
     * @return IEmbedFeature
     */
    public function governanceIssues(array $params, ?ActingUser $as = null)
    {
        return new GovernanceIssuesEmbed($this->core, $params, $as);
    }

    public function pageFigures(array $params, ?ActingUser $as = null)
    {
        return new PageFiguresEmbed($this->core, $params, $as);
    }
}
