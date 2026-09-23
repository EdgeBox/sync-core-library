<?php

namespace EdgeBox\SyncCore\V2\Embed;

use EdgeBox\SyncCore\Interfaces\Embed\IEmbedFeature;
use EdgeBox\SyncCore\Interfaces\Embed\IEmbedService;
use EdgeBox\SyncCore\Interfaces\Embed\PageFiguresBoxParams;
use EdgeBox\SyncCore\Interfaces\Governance\ActingUser;
use EdgeBox\SyncCore\Interfaces\IApplicationInterface;
use EdgeBox\SyncCore\V2\SyncCore;

class PageFiguresEmbed extends Embed implements IEmbedFeature
{
    protected $params;

    /**
     * The box sits in a site's edit form, where a collapsed `details` element
     * or a tab that is not selected often hides it when the page loads, so it
     * is measured again each time it is uncovered.
     *
     * @var bool
     */
    protected $remeasureOnUncover = true;

    public function __construct(SyncCore $core, array $params, ?ActingUser $as = null)
    {
        parent::__construct(
            $core,
            IEmbedService::BOX_PAGE_FIGURES,
            IApplicationInterface::SYNC_CORE_PERMISSIONS_CONTENT,
            $as
        );

        $figures = new PageFiguresBoxParams($params);

        $this->params = $figures->toOptions() + [self::OPTION_SIZE => self::SIZE_BOX];
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
