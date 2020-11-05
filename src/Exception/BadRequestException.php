<?php

namespace EdgeBox\SyncCore\Exception;

/**
 * The Sync core responded with 400 Bad Request.
 *
 * For Ping requests:
 * - The URL this site provided is not valid.
 * - This will also be thrown if you provide any localhost domain or raw IP address.
 */
class BadRequestException extends SyncCoreException {
}
