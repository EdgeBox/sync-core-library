<?php

namespace EdgeBox\SyncCore\Exception;

/**
 * The Sync Core responded with 404 Not Found.
 *
 * For ping requests:
 * - The Sync Core didn't get a valid response from the site. This can mean
 * that traffic is blocked, issues with REST interface configuration.
 */
class NotFoundException extends SyncCoreException {

}
