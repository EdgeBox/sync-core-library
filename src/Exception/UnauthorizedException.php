<?php

namespace EdgeBox\SyncCore\Exception;

/**
 * The Sync core responded with 401 Unauthorized.
 *
 * For site registration requests:
 * - The JWT has a very short lifetime of only 5 minutes. Any request after that will result in an "unauthorized" exception
 *   and you have to restart the process.
 */
class UnauthorizedException extends SyncCoreException
{
}
