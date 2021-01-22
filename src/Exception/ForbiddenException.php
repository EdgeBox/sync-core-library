<?php

namespace EdgeBox\SyncCore\Exception;

/**
 * The Sync core responded with 403 Forbidden.
 *
 * For Ping requests:
 * - The Sync Core is not allowed to run the HTTP requests against this site.
 * - This can mean that the credentials aren't valid, the site is blocking
 * login requests or the REST interface doesn't allow the selected
 * authentication type.
 */
class ForbiddenException extends SyncCoreException {

}
