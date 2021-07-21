<?php

namespace EdgeBox\SyncCore\V2\Embed;

use EdgeBox\SyncCore\Helpers\EmbedResult;
use EdgeBox\SyncCore\Interfaces\Embed\IEmbedFeature;
use EdgeBox\SyncCore\Interfaces\Embed\IEmbedService;
use EdgeBox\SyncCore\Interfaces\IApplicationInterface;
use EdgeBox\SyncCore\V2\Raw\Model\CreateSiteDto;
use EdgeBox\SyncCore\V2\Raw\Model\SiteApplicationType;
use EdgeBox\SyncCore\V2\SyncCore;

class RegisterSiteEmbed extends Embed implements IEmbedFeature
{
    protected $params;

    public function __construct(SyncCore $core, array $params, string $type = '')
    {
        $this->params = $params;

        parent::__construct(
            $core,
            $type ? $type : IEmbedService::REGISTER_SITE,
            // Set this to "none" as the site is not yet registered, so it can't make
            // any requests to the Sync Core yet.
            empty($this->params['jwt']) && !$core->getApplication()->getSiteUuid() ? '' : IApplicationInterface::SYNC_CORE_PERMISSIONS_CONFIGURATION
        );
    }

    public function run()
    {
        if ($this->shouldRegisterSite()) {
            $this->core->registerSiteWithJwt($this->params);

            return new EmbedResult(
                EmbedResult::TYPE_REDIRECT,
                $this->core->getApplication()->getEmbedBaseUrl(IEmbedService::SITE_REGISTERED)
            );
        }

        return $this->render();
    }

    protected function shouldRegisterSite()
    {
        return !empty($this->params['jwt']);
    }

    protected function getOptions()
    {
        $application = $this->core->getApplication();

        $siteDto = new CreateSiteDto();
        /**
         * @var SiteApplicationType $app_type
         */
        $app_type = $application->getApplicationId();
        $siteDto->setAppType($app_type);
        $siteDto->setAppVersion($application->getApplicationVersion());
        $siteDto->setAppModuleVersion($application->getApplicationModuleVersion());
        $siteDto->setBaseUrl($application->getSiteBaseUrl());
        $siteDto->setName($application->getSiteName());

        $uuid = $application->getSiteUuid();
        if ($uuid) {
            $siteDto->setUuid($uuid);
        }

        $options = (array) $siteDto->jsonSerialize();

        // Redirect to this page.
        $options['redirectUrl'] = $application->getEmbedBaseUrl(IEmbedService::REGISTER_SITE);

        $options['migrated'] = $this->params['migrated'];

        return $options;
    }
}
