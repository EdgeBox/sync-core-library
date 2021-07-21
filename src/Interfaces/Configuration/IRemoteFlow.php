<?php

namespace EdgeBox\SyncCore\Interfaces\Configuration;

interface IRemoteFlow
{
    /**
     * @return null|string
     */
    public function getConfig();

    /**
     * @return string
     */
    public function getName();

    /**
     * @return string
     */
    public function getSiteName();
}
