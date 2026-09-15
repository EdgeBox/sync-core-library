<?php

namespace EdgeBox\SyncCore\V2\Governance;

use EdgeBox\SyncCore\Interfaces\Governance\IIssueContext;

class IssueContext implements IIssueContext
{
    /**
     * @var array
     */
    protected $data;

    public function __construct(array $data)
    {
        $this->data = $data;
    }

    public function getKey()
    {
        return (string) ($this->data['key'] ?? '');
    }

    public function getTypeKey()
    {
        return (string) ($this->data['typeKey'] ?? '');
    }

    public function getTypeName()
    {
        return (string) ($this->data['typeName'] ?? '');
    }

    public function getPriority()
    {
        return $this->data['priority'] ?? null;
    }

    public function getInstruction()
    {
        return $this->data['instruction'] ?? null;
    }

    public function getEvidence()
    {
        return $this->data['evidence'] ?? null;
    }
}
