<?php

namespace EdgeBox\SyncCore\Interfaces;

interface IReportingService
{
    public const LOG_LEVEL_ERROR = 'error';

    public const LOG_LEVEL_WARNING = 'warn';

    /**
     * @param null|string|string[] $level
     *                                    See self::LOG_LEVEL*
     *
     * @return null|array
     */
    public function getLog($level = null);

    /**
     * @throws \EdgeBox\SyncCore\Exception\SyncCoreException
     *
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
     */
    public function getStatus();
}
