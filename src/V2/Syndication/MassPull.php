<?php

namespace EdgeBox\SyncCore\V2\Syndication;

use EdgeBox\SyncCore\Interfaces\Syndication\IMassPull;
use EdgeBox\SyncCore\V2\Raw\Model\MigrationType;
use EdgeBox\SyncCore\V2\SyncCore;

class MassPull extends MassUpdate implements IMassPull
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
        return $this->executeWithType($this->initial ? MigrationType::MAP_EXISTING_BY_ID : MigrationType::PULL_ALL);
    }

    protected function getDtos()
    {
        $this->getDtosWithTypes([
            $this->initial ? MigrationType::MAP_EXISTING_BY_ID : MigrationType::PULL_ALL,
        ]);
    }
}
