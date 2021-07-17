<?php

namespace EdgeBox\SyncCore\Interfaces\Syndication;

use EdgeBox\SyncCore\Exception\SyncCoreException;
use EdgeBox\SyncCore\Interfaces\IProgress;

interface IPullAll extends IProgress
{
    /**
     * @return $this
     */
    public function fromPool(string $pool_id);

    /**
     * @return $this
     */
    public function force(bool $set);

    /**
     * @return bool
     */
    public function hasFinished();

    /**
     * @return bool
     */
    public function hasFailed();

    /**
     * @return bool
     */
    public function wasAborted();

    /**
     * @return string
     */
    public function getTypeMachineName();

    /**
     * @return string
     */
    public function getBundleMachineName();

    /**
     * @return string
     */
    public function getSourceName();

    /**
     * @return $this
     *
     * @throws SyncCoreException
     */
    public function execute();

    /**
     * @param bool $fromCache
     *
     * @return int
     */
    public function progress($fromCache = false);
}
