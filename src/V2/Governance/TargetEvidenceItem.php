<?php

namespace EdgeBox\SyncCore\V2\Governance;

use EdgeBox\SyncCore\Interfaces\Governance\ITargetEvidenceItem;

class TargetEvidenceItem implements ITargetEvidenceItem
{
    /**
     * @var array
     */
    protected $data;

    public function __construct(array $data)
    {
        $this->data = $data;
    }

    public function getPath()
    {
        return (string) ($this->data['path'] ?? '');
    }

    public function getValue()
    {
        return $this->data['value'] ?? null;
    }

    public function wasTruncated()
    {
        return (bool) ($this->data['isTruncated'] ?? false);
    }

    public function getReasoning()
    {
        return $this->data['reasoning'] ?? null;
    }
}
