<?php

namespace EdgeBox\SyncCore\V2\Governance;

use EdgeBox\SyncCore\Interfaces\Governance\INamedReference;

class NamedReference implements INamedReference
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

    public function getName()
    {
        return (string) ($this->data['name'] ?? '');
    }
}
