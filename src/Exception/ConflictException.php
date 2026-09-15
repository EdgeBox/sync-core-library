<?php

namespace EdgeBox\SyncCore\Exception;

/**
 * The Sync Core responded with 409 Conflict.
 *
 * The request could not be completed because it clashes with the current state
 * of the resource, for example an external draft posted under a key that another
 * optimization already holds. A handler for SyncCoreException still catches it.
 */
class ConflictException extends SyncCoreException {}
