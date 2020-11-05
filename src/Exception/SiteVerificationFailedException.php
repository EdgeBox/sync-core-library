<?php

namespace SyncCore\Exception;

/**
 * A site with this site's ID but a different base URL already exists. Deny
 * to continue.
 */
class SiteVerificationFailedException extends SyncCoreException {}
