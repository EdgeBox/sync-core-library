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

    public function getFlow(): ?string;

    /**
     * @return $this
     */
    public function withNamespaceMachineName(string $entity_type_name);

    public function getNamespaceMachineName(): ?string;

    /**
     * @return $this
     */
    public function withEntityTypeMachineName(string $bundle_name);

    public function getEntityTypeMachineName(): ?string;

    /**
     * @return $this
     */
    public function withEntityTypeVersion(string $version_id);

    public function getEntityTypeVersion(): ?string;

    /**
     * @return $this
     */
    public function usingMigrationType(string $type);

    public function getMigrationType(): string;

    /**
     * @return $this
     */
    public function isInitialMigration(bool $is_initial);

    /**
     * @return bool
     */
    public function includeOtherSites(?bool $set);

    /**
     * @return $this
     *
     * @throws SyncCoreException
     */
    public function execute();
}
