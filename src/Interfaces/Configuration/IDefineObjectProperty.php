<?php

namespace EdgeBox\SyncCore\Interfaces\Configuration;

interface IDefineObjectProperty extends IDefineProperty, IDefineObject
{
    /**
     * Provide the machine name of the property that holds the most important
     * value of the object, if it has one.
     *
     * @return $this
     */
    public function setMainProperty(string $machine_name);

    /**
     * Provide the machine name of the property that's used to identify the
     * object.
     *
     * @return $this
     */
    public function setNameProperty(string $machine_name);
}
