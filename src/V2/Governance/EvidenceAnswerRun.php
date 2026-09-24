<?php

namespace EdgeBox\SyncCore\V2\Governance;

use EdgeBox\SyncCore\Interfaces\Governance\IEvidenceAnswerRun;

class EvidenceAnswerRun implements IEvidenceAnswerRun
{
    /**
     * @var array
     */
    protected $data;

    public function __construct(array $data)
    {
        $this->data = $data;
    }

    public function getId()
    {
        return (string) ($this->data['id'] ?? '');
    }

    public function getEngine()
    {
        $engine = $this->data['engine'] ?? null;

        return new NamedReference(is_array($engine) ? $engine : []);
    }

    public function getPromptText()
    {
        return $this->data['promptText'] ?? null;
    }

    public function getAnsweredAt()
    {
        return (string) ($this->data['answeredAt'] ?? '');
    }
}
