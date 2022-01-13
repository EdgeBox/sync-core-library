<?php

namespace EdgeBox\SyncCore\Interfaces\Syndication;

use EdgeBox\SyncCore\Exception\SyncCoreException;
use EdgeBox\SyncCore\Interfaces\IProgressWithStatus;

interface IMassPush extends IProgressWithStatus
{
    /**
     * @return $this
     */
    public function withFlow(string $flow_id);

    /**
     * @return string
     */
    public function getFlow(): ?string;

    /**
     * @return $this
     */
    public function withNamespaceMachineName(string $entity_type_name);

    /**
     * @return string
     */
    public function getNamespaceMachineName(): ?string;

    /**
     * @return $this
     */
    public function withEntityTypeMachineName(string $bundle_name);

    /**
     * @return string
     */
    public function getEntityTypeMachineName(): ?string;

    /**
     * @return $this
     */
    public function withEntityTypeVersion(string $version_id);

    /**
     * @return string
     */
    public function getEntityTypeVersion(): ?string;

    /**
     * @return $this
     */
    public function isInitialMigration(bool $is_initial);

    /**
     * @return bool
     */
    public function includeOtherSites(?bool $set);

    /**
     * @throws SyncCoreException
     *
     * @return $this
     */
    public function execute();
}
