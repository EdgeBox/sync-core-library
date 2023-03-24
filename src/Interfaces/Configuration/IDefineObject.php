<?php

namespace EdgeBox\SyncCore\Interfaces\Configuration;

use EdgeBox\SyncCore\Interfaces\IBatchOperation;

interface IDefineObject extends IBatchOperation
{
    /**
     * @param bool $multiple
     * @param bool $required
     */
    public function addBooleanProperty(string $machine_name, ?string $name, $multiple = false, $required = false, ?string $type_name = null);

    /**
     * @param bool $multiple
     * @param bool $required
     */
    public function addIntegerProperty(string $machine_name, ?string $name, $multiple = false, $required = false, ?string $type_name = null);

    /**
     * @param bool $multiple
     * @param bool $required
     */
    public function addFloatProperty(string $machine_name, ?string $name, $multiple = false, $required = false, ?string $type_name = null);

    /**
     * @param bool $multiple
     * @param bool $required
     */
    public function addStringProperty(string $machine_name, ?string $name, $multiple = false, $required = false, ?string $type_name = null);

    /**
     * @param bool $multiple
     * @param bool $required
     *
     * @return IDefineObject
     */
    public function addObjectProperty(string $machine_name, ?string $name, $multiple = false, $required = false, ?string $type_name = null);

    /**
     * @param bool $multiple
     * @param bool $required
     */
    public function addReferenceProperty(string $machine_name, ?string $name, $multiple = false, $required = false, ?string $type_name = null);
}
