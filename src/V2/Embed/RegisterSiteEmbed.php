<?php

namespace EdgeBox\SyncCore\V2\Embed;

use EdgeBox\SyncCore\Helpers\EmbedResult;
use EdgeBox\SyncCore\Interfaces\Embed\IEmbedFeature;
use EdgeBox\SyncCore\Interfaces\Embed\IEmbedService;
use EdgeBox\SyncCore\V2\Raw\Model\CreateSiteDto;
use EdgeBox\SyncCore\V2\Raw\Model\SiteApplicationType;
use EdgeBox\SyncCore\V2\SyncCore;

class RegisterSiteEmbed extends Embed implements IEmbedFeature
{
    protected $params;

    public function __construct(SyncCore $core, array $params)
    {
        $this->params = $params;

        parent::__construct(
        $core,
            IEmbedService::REGISTER_SITE,
            // Set this to "none" as the site is not yet registered, so it can't make
        // any requests to the Sync Core yet.
            ''
    );
    }

    public function run()
    {
        if (!empty($this->params['jwt'])) {
            $this->core->registerSiteWithJwt($this->params);

            return new EmbedResult(
        EmbedResult::TYPE_REDIRECT,
        $this->core->getApplication()->getEmbedBaseUrl(IEmbedService::SITE_REGISTERED)
      );
        }

        return $this->render();
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

        $options = (array) $siteDto->jsonSerialize();

        // Redirect to this page.
        $options['redirectUrl'] = $application->getEmbedBaseUrl(IEmbedService::REGISTER_SITE);

        return $options;
    }
}
