<?php

namespace EdgeBox\SyncCore\Interfaces\Embed;

interface IEmbedService
{
    public const REGISTER_SITE = 'register-site';
    public const PULL_DASHBOARD = 'pull-dashboard';
    public const SITE_REGISTERED = 'site-registered';

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
}
