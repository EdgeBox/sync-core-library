<?php

namespace EdgeBox\SyncCore\Interfaces\Aim;

/**
 * The record Sync Core returns for an externally authored draft.
 */
interface IExternalDraftResult
{
    /**
     * @return string
     */
    public function getOptimizationId();

    /**
     * @return string
     */
    public function getStatus();

    /**
     * @return bool true when the post was an idempotent repeat
     */
    public function wasExisting();
}
