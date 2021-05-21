<?php

namespace EdgeBox\SyncCore\Interfaces\Configuration;

interface IRemoteFlowListItem
{
    /**
     * @return string
     */
    // TODO: Drupal: Must distinguish between machine name and ID. Must pass the ID when trying
    //  to copy a flow.
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
