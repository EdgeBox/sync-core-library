<?php

namespace EdgeBox\SyncCore\V2\Syndication;

use EdgeBox\SyncCore\Interfaces\Syndication\IMassPush;
use EdgeBox\SyncCore\V2\Raw\Model\MigrationType;
use EdgeBox\SyncCore\V2\SyncCore;

class MassPush extends MassUpdate implements IMassPush
{
    /**
     * PullAll constructor.
     */
    public function __construct(SyncCore $core)
    {
        parent::__construct($core);
    }

    /**
     * {@inheritdoc}
     */
    public function execute()
    {
        return $this->executeWithType(MigrationType::PUSH_ALL);
    }

    protected function getDtos()
    {
        $this->getDtosWithTypes([
            MigrationType::PUSH_ALL,
        ]);
    }
}
