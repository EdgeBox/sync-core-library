<?php

namespace EdgeBox\SyncCore\Interfaces;

use EdgeBox\SyncCore\Exception\SyncCoreException;

interface IReportingService
{
    public const LOG_LEVEL_ERROR = 'error';

    public const LOG_LEVEL_WARNING = 'warn';

    /**
     * @param string[]|string|null $level See self::LOG_LEVEL*
     *
     * @return array|null
     */
    public function getLog($level = null);

    /**
     * @return array
     *
     * Structure is:
     * ['version'] => string,
     * ['usage']['site']['monthly'|'daily'|'hourly']['updateCount'] => int,
     * ['usage']['contract']['monthly']['updateCount'] => int,
     * All usage items are optional and may not be defined
     *
     * @throws SyncCoreException
     */
    public function getStatus();
}
