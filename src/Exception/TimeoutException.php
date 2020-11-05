<?php

namespace SyncCore\Exception;

/**
 * The Sync Core did not respond in time. Might be a temporary overload so if the
 * request was done through the CLI, try waiting and repeating it until it
 * comes through or the user cancels it.
 */
class TimeoutException extends SyncCoreException {}
