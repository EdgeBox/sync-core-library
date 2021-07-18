<?php

namespace EdgeBox\SyncCore\Interfaces\Configuration;

use EdgeBox\SyncCore\Interfaces\IBatchOperation;

interface IFlowPullConfiguration extends IBatchOperation
{
    /**
     * Only apply syndication if one of the given entities is referenced. This
     * allows users to syndicate content based on simple tagging.
     *
     * @param string[] $allowed_entity_ids
     *
     * @return $this
     */
    public function ifTaggedWith(string $property, array $allowed_entity_ids);

    /**
     * @return $this
     */
    public function pullDeletions(bool $set);

    /**
     * @return IFlowPullConfiguration|null
     */
    public function configureOverride(string $flow_id);

    /**
     * @return $this
     */
    public function manually(bool $set);

    /**
     * @return $this
     */
    public function asDependency(bool $set);
}
