<?php

namespace EdgeBox\SyncCore\Interfaces\Embed;

use EdgeBox\SyncCore\Interfaces\Governance\ActingUser;

interface IEmbedService
{
    public const REGISTER_SITE = 'register-site';
    public const PULL_DASHBOARD = 'pull-dashboard';
    public const SITE_REGISTERED = 'site-registered';
    public const SITE_SETTINGS = 'site-settings';
    public const ENTITY_STATUS = 'entity-status';
    public const OPTIMIZE = 'optimize';
    public const BOX_UPDATE_STATUS = 'box.update-status';
    public const MIGRATE = 'migrate';
    public const FLOW_FORM = 'flow-form';
    public const SYNDICATION_DASHBOARD = 'syndication-dashboard';

    // The value is the embed's id on the wire and, with the dot read as a
    // slash, the route people land on. Both carry the name the product shows
    // its users, which is not the name this code gives the topic.
    public const GOVERNANCE_CONTENT_INVENTORY = 'brand-presence.content-inventory';

    public const BOX_PAGE_FIGURES = 'box.page-figures';

    /**
     * @return IEmbedFeature
     */
    public function registerSite(?array $params);

    /**
     * @return IEmbedFeature
     */
    public function siteRegistered(?array $params);

    /**
     * @return IEmbedFeature
     */
    public function siteSettings(?array $params);

    /**
     * @return IEmbedFeature
     */
    public function pullDashboard(?array $params);

    /**
     * @return IEmbedFeature
     */
    public function entityStatus(array $params);

    /**
     * @return IEmbedFeature
     */
    public function optimize(array $params);

    /**
     * @return IEmbedFeature
     */
    public function updateStatusBox(array $params);

    /**
     * @return IEmbedFeature
     */
    public function migrate(array $params);

    /**
     * @return IEmbedFeature
     */
    public function flowForm(array $params);

    /**
     * @return IEmbedFeature
     */
    public function syndicationDashboard(array $params);

    /**
     * @return IEmbedFeature
     */
    public function governanceContentInventory(array $params, ?ActingUser $as = null);

    /**
     * The box that renders one page's figures, sized to fill the region it is
     * placed in. The figures travel from the site's own storage into the frame
     * markup the site renders; this makes no request of its own.
     *
     * The frame is measured again each time it is uncovered, so a site may
     * place it in a collapsed `details` element or a tab that is not selected
     * and needs no script of its own to size it once it is shown.
     *
     * @return IEmbedFeature
     */
    public function pageFigures(PageFiguresBoxParams $figures);
}
