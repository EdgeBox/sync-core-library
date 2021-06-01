<?php

namespace EdgeBox\SyncCore\Interfaces;

interface IFeatureFlags
{
    /**
     * @return bool
     */
    public function isEnabled(string $name);
}
