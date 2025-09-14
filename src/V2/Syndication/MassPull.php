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

    public function usingMigrationType(string $type)
    {
        if (!in_array($type, [
            MigrationType::PULL_ALL,
            MigrationType::PULL_CHANGED,
            MigrationType::PULL_FAILED,
            MigrationType::RETRIEVE_FAILED,
            MigrationType::PULL_ALL_LIMIT_EXCEEDED,
            MigrationType::MAP_EXISTING_BY_ID,
        ])) {
            throw new \InvalidArgumentException('Migration type '.$type.' is not allowed.');
        }

        $this->migrationType = $type;

        return $this;
    }

    public function getMigrationType(): string
    {
        if ($this->migrationType) {
            return $this->migrationType;
        }

        return $this->initial ? MigrationType::MAP_EXISTING_BY_ID : MigrationType::PULL_ALL;
    }
}
