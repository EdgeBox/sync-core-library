<?php

namespace EdgeBox\SyncCore\V2\Embed;

use EdgeBox\SyncCore\V2\Raw\Model\CreateSiteDto;
use EdgeBox\SyncCore\V2\SyncCore;

// TODO: Library: add interface.
class RegisterSiteEmbed extends Embed
{
    protected $params;

    public function __construct(SyncCore $core, array $params)
    {
        $this->params = $params;

        parent::__construct(
        $core,
        Embed::REGISTER_SITE,
        SyncCore::SYNC_CORE_PERMISSIONS_CONFIGURATION
    );
    }

    public function run()
    {
        if (!empty($this->params['jwt'])) {
            $this->core->registerSiteWithJwt($this->params);

            return new EmbedResult(
        EmbedResult::TYPE_REDIRECT,
        $this->core->getApplication()->getEmbedBaseUrl(Embed::SITE_REGISTERED)
      );
        }

        return $this->render();
    }

    protected function getOptions()
    {
        $application = $this->core->getApplication();

        $siteDto = new CreateSiteDto();
        $siteDto->setAppType($application->getApplicationId());
        $siteDto->setAppVersion($application->getApplicationVersion());
        $siteDto->setAppModuleVersion($application->getApplicationModuleVersion());
        $siteDto->setBaseUrl($application->getSiteBaseUrl());
        $siteDto->setName($application->getSiteName());

        $options = (array) $siteDto->jsonSerialize();

        // Redirect to this page.
        $options['redirectUrl'] = $application->getEmbedBaseUrl(Embed::REGISTER_SITE);

        return $options;
    }
}
