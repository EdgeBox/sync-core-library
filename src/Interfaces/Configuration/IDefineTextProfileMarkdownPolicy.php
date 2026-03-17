<?php

namespace EdgeBox\SyncCore\Interfaces\Configuration;

interface IDefineTextProfileMarkdownPolicy
{
    /**
     * @param mixed $set
     *
     * @return $this
     */
    public function setDialect($set);

    /**
     * @return $this
     */
    public function setDisallowRawHtml(bool $set);
}
