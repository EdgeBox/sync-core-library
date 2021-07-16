<?php

namespace EdgeBox\SyncCore\Interfaces\Embed;

interface IEmbedService
{
    public const REGISTER_SITE = 'register-site';
    public const PULL_DASHBOARD = 'pull-dashboard';
    public const SITE_REGISTERED = 'site-registered';
    public const ENTITY_STATUS = 'entity-status';
    public const MIGRATE = 'migrate';
    public const SYNDICATION_DASHBOARD = 'syndication-dashboard';

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
    public function pullDashboard(?array $params);

    /**
     * @return IEmbedFeature
     */
    public function entityStatus(array $params);

    /**
     * @return IEmbedFeature
     */
    public function migrate(array $params);

    /**
     * @return IEmbedFeature
     */
    public function syndicationDashboard(array $params);
}
