<?php

namespace EdgeBox\SyncCore\V2\Governance;

use EdgeBox\SyncCore\Interfaces\Governance\IEvidenceCoverage;

class EvidenceCoverage implements IEvidenceCoverage
{
    /**
     * @var array
     */
    protected $data;

    public function __construct(array $data)
    {
        $this->data = $data;
    }

    public function getEngine()
    {
        $engine = $this->data['engine'] ?? null;

        return new NamedReference(is_array($engine) ? $engine : []);
    }

    public function getPurpose()
    {
        $purpose = $this->data['purpose'] ?? null;

        return new NamedReference(is_array($purpose) ? $purpose : []);
    }

    public function getAnswers()
    {
        return (int) ($this->data['answers'] ?? 0);
    }
}
