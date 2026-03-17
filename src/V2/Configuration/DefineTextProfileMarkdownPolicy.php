<?php

namespace EdgeBox\SyncCore\V2\Configuration;

use EdgeBox\SyncCore\Interfaces\Configuration\IDefineTextProfileMarkdownPolicy;
use EdgeBox\SyncCore\V2\Raw\Model\MarkdownPolicy;

class DefineTextProfileMarkdownPolicy implements IDefineTextProfileMarkdownPolicy
{
    /**
     * @var MarkdownPolicy
     */
    protected $dto;

    public function __construct(MarkdownPolicy $dto)
    {
        $this->dto = $dto;
    }

    public function setDialect($set)
    {
        $this->dto->setDialect($set);

        return $this;
    }

    public function setDisallowRawHtml(bool $set)
    {
        $this->dto->setDisallowRawHtml($set);

        return $this;
    }
}
