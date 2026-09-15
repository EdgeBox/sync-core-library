<?php

namespace EdgeBox\SyncCore\Interfaces\Aim;

use EdgeBox\SyncCore\Exception\BadRequestException;
use EdgeBox\SyncCore\Exception\ConflictException;
use EdgeBox\SyncCore\Exception\ForbiddenException;
use EdgeBox\SyncCore\Exception\NotFoundException;
use EdgeBox\SyncCore\Exception\SyncCoreException;

/**
 * Builds and sends the post of an externally authored draft.
 */
interface IPostExternalDraft
{
    /**
     * @return $this
     */
    public function forOptimization(string $optimization_id);

    /**
     * @return $this
     */
    public function withOptimizationTypeKeys(array $keys);

    /**
     * @return $this
     */
    public function fixingIssues(array $issue_keys);

    /**
     * @return IExternalDraftResult
     *
     * @throws ConflictException   another optimization holds the live Suggestion under this key
     * @throws BadRequestException malformed, or over the size bound
     * @throws ForbiddenException  the content item is not this site's
     * @throws NotFoundException   no such optimization or content item
     * @throws SyncCoreException
     */
    public function execute();
}
