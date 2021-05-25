<?php

namespace EdgeBox\SyncCore\Interfaces\Configuration;

interface IRemoteFlowListItem
{
    /**
     * @return string
     */
    public function getId();

    /**
     * @return string
     */
    public function getMachineName();

    /**
     * @return string
     */
    public function getName();

    /**
     * @return string
     */
    public function getSiteName();
}
