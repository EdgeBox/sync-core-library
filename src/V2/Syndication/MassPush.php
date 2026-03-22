<?php

namespace EdgeBox\SyncCore\V2\Syndication;

use EdgeBox\SyncCore\Interfaces\Syndication\IMassPush;
use EdgeBox\SyncCore\V2\Raw\Model\TaskGroupType;
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

    public function usingMigrationType(string $type)
    {
        if (!in_array($type, [
            TaskGroupType::PUSH_ALL,
            TaskGroupType::PUSH_ALL_LATEST,
            TaskGroupType::PUSH_FAILED,
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

        return TaskGroupType::PUSH_ALL;
    }
}
