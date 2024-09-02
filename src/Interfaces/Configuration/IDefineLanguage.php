<?php

namespace EdgeBox\SyncCore\Interfaces\Configuration;

use EdgeBox\SyncCore\Interfaces\IBatchOperation;

interface IDefineLanguage extends IBatchOperation
{
    /**
     * Define whether the language is read right-to-left or left-to-right.
     *
     * @param null|bool $set
     *
     * @return bool
     */
    public function isRightToLeft($set = null);

    /**
     * Define the name of the language in the language itself.
     *
     * @param null|string $set
     *
     * @return string
     */
    public function setNativeName($set = null);
}
