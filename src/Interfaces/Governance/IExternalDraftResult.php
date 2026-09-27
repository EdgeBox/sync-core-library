<?php

namespace EdgeBox\SyncCore\Interfaces\Governance;

/**
 * The record Sync Core returns for an externally authored draft: after its
 * post, or after a report of what became of it.
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
     * @return bool true when the Sync Core changed nothing: for a post, an
     *              idempotent repeat; for a report of what became of the
     *              draft, a repeat or a report about an optimization that had
     *              already ended another way, which the Sync Core ignores
     */
    public function wasExisting();
}
