<?php

namespace EdgeBox\SyncCore\Interfaces\Configuration;

interface IDefineReferenceProperty extends IDefineProperty
{
    /**
     * Restrict the entities that are allowed to be referenced to the given types.
     *
     * @return $this
     */
    public function allowType(string $namespaceMachineName, ?string $machineName);
}
