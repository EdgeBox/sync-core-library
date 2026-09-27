<?php

namespace EdgeBox\SyncCore\Interfaces\Governance;

use EdgeBox\SyncCore\Exception\BadRequestException;
use EdgeBox\SyncCore\Exception\ConflictException;
use EdgeBox\SyncCore\Exception\ForbiddenException;
use EdgeBox\SyncCore\Exception\NotFoundException;
use EdgeBox\SyncCore\Exception\SyncCoreException;
use EdgeBox\SyncCore\Exception\UnauthorizedException;

/**
 * Sends a site's report of what became of a draft it wrote for an
 * optimization itself.
 */
interface IReportExternalDraftOutcome
{
    /**
     * @return IExternalDraftResult the optimization as the report left it;
     *                              wasExisting() is true when the report changed
     *                              nothing: a repeat, or a report about an
     *                              optimization that ended otherwise
     *
     * @throws BadRequestException   malformed, an outcome the Sync Core does not know, or a
     *                               publication whose page the Sync Core could not queue for
     *                               its re-measurement; the optimization is unchanged then
     * @throws UnauthorizedException the site's credentials were not accepted
     * @throws ForbiddenException    the optimization is not this site's
     * @throws NotFoundException     no such optimization
     * @throws ConflictException     the optimization carries no draft with this revision identifier
     * @throws SyncCoreException
     */
    public function execute();
}
