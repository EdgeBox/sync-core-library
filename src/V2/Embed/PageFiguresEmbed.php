<?php

namespace EdgeBox\SyncCore\V2\Embed;

use EdgeBox\SyncCore\Interfaces\Embed\IEmbedFeature;
use EdgeBox\SyncCore\Interfaces\Embed\IEmbedService;
use EdgeBox\SyncCore\Interfaces\Embed\PageFiguresBoxParams;
use EdgeBox\SyncCore\Interfaces\IApplicationInterface;
use EdgeBox\SyncCore\V2\SyncCore;

class PageFiguresEmbed extends Embed implements IEmbedFeature
{
    protected $params;

    public function __construct(SyncCore $core, PageFiguresBoxParams $figures)
    {
        // The figures are the site's own and nothing in the box is attributable
        // to a person, so the frame carries the site's token and no acting user.
        parent::__construct(
            $core,
            IEmbedService::BOX_PAGE_FIGURES,
            IApplicationInterface::SYNC_CORE_PERMISSIONS_CONTENT
        );

        // The size is the library's to decide, and it is stated first so it
        // holds whatever an option carries.
        $this->params = [self::OPTION_SIZE => self::SIZE_BOX] + $figures->toOptions();
    }

    public function run()
    {
        return $this->render();
    }

    protected function getOptions()
    {
        return $this->params;
    }
}
