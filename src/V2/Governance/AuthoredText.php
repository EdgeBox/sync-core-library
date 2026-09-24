<?php

namespace EdgeBox\SyncCore\V2\Governance;

use EdgeBox\SyncCore\Interfaces\Governance\IAuthoredText;

class AuthoredText implements IAuthoredText
{
    /**
     * @var array
     */
    protected $data;

    public function __construct(array $data)
    {
        $this->data = $data;
    }

    public function getText()
    {
        return (string) ($this->data['text'] ?? '');
    }

    public function getFormat()
    {
        return (string) ($this->data['format'] ?? '');
    }

    public function getProvenance()
    {
        return (string) ($this->data['provenance'] ?? '');
    }

    public function getAt()
    {
        return (string) ($this->data['at'] ?? '');
    }
}
