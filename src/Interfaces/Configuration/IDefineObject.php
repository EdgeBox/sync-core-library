<?php

namespace EdgeBox\SyncCore\Interfaces\Configuration;

interface IDefineObject
{
    /**
     * @param bool $multiple
     * @param bool $required
     *
     * @return IDefineBooleanProperty
     */
    public function addBooleanProperty(string $machine_name, ?string $name, $multiple = false, $required = false, ?string $type_name = null);

    /**
     * @param bool $multiple
     * @param bool $required
     *
     * @return IDefineIntegerProperty
     */
    public function addIntegerProperty(string $machine_name, ?string $name, $multiple = false, $required = false, ?string $type_name = null);

    /**
     * @param bool $multiple
     * @param bool $required
     *
     * @return IDefineFloatProperty
     */
    public function addFloatProperty(string $machine_name, ?string $name, $multiple = false, $required = false, ?string $type_name = null);

    /**
     * @param bool $multiple
     * @param bool $required
     *
     * @return IDefineStringProperty
     */
    public function addStringProperty(string $machine_name, ?string $name, $multiple = false, $required = false, ?string $type_name = null);

    /**
     * @param bool $multiple
     * @param bool $required
     *
     * @return IDefineObjectProperty
     */
    public function addObjectProperty(string $machine_name, ?string $name, $multiple = false, $required = false, ?string $type_name = null);

    /**
     * @param bool $multiple
     * @param bool $required
     *
     * @return IDefineReferenceProperty
     */
    public function addReferenceProperty(string $machine_name, ?string $name, $multiple = false, $required = false, ?string $type_name = null);
}
