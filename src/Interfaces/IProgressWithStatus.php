<?php

namespace EdgeBox\SyncCore\Interfaces;

interface IProgressWithStatus extends IProgress
{
    /**
     * Return an array of [ (string)status => (int)count ].
     *
     * @return array
     */
    public function getByStatus();
}
