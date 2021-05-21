<?php

namespace EdgeBox\SyncCore\Interfaces;

interface IReportingService
{
    public const LOG_LEVEL_ERROR = 'error';

    public const LOG_LEVEL_WARNING = 'warn';

    /**
     * @param string[]|string|null $level
     *                                    See self::LOG_LEVEL*
     *
     * @return array|null
     */
    public function getLog($level = null);

    /**
     * @return array[
     *                'version' => '...'
     *                'usage' => [
     *                'today' => [
     *                'entitiesPushedFromSites' => ...
     *                'rootEntitiesPushedFromSites' => ...
     *                'entitiesPulledBySites' => ...
     *                'rootEntitiesPulledBySites' => ...
     *                ]
     *                ]
     *                ]
     *
     * @throws \EdgeBox\SyncCore\Exception\SyncCoreException
     */
    public function getStatus();
}
