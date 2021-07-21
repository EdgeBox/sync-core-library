<?php

namespace EdgeBox\SyncCore\Interfaces;

use EdgeBox\SyncCore\Exception\SyncCoreException;

interface IReportingService
{
    public const LOG_LEVEL_ERROR = 'error';

    public const LOG_LEVEL_WARNING = 'warn';

    /**
     * @param null|string|string[] $level See self::LOG_LEVEL*
     *
     * @return null|array
     */
    public function getLog($level = null);

    /**
     * @throws SyncCoreException
     *
     * @return array
     *
     * Structure is:
     * ['version'] => string,
     * ['usage']['site']['monthly'|'daily'|'hourly']['updateCount'] => int,
     * ['usage']['contract']['monthly']['updateCount'] => int,
     * All usage items are optional and may not be defined
     */
    public function getStatus();
}
