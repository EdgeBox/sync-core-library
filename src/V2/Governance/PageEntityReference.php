<?php

namespace EdgeBox\SyncCore\V2\Governance;

use EdgeBox\SyncCore\Interfaces\Governance\IPageEntityReference;

class PageEntityReference implements IPageEntityReference
{
    /**
     * @var array
     */
    protected $data;

    public function __construct(array $data)
    {
        $this->data = $data;
    }

    public function getEntityType()
    {
        return (string) ($this->data['entityType'] ?? '');
    }

    public function getRemoteUuid()
    {
        return (string) ($this->data['remoteUuid'] ?? '');
    }

    public function getLangcode()
    {
        return (string) ($this->data['langcode'] ?? '');
    }
}
