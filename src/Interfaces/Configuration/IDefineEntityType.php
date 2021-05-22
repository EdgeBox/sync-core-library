<?php

namespace EdgeBox\SyncCore\Interfaces\Configuration;

use EdgeBox\SyncCore\Interfaces\IBatchOperation;

interface IDefineEntityType extends IBatchOperation
{
    /**
     * @return $this
     */
    public function isTranslatable(bool $set);

    /**
     * @return $this
     */
    public function isFile(bool $set);

    /**
     * @param bool $multiple
     * @param bool $required
     *
     * @return $this
     */
    // TODO: Drupal: Pass property name, not only machine name.
    public function addBooleanProperty(string $machine_name, string $name, $multiple = false, $required = false);

    /**
     * @param bool $multiple
     * @param bool $required
     *
     * @return $this
     */
    public function addIntegerProperty(string $machine_name, string $name, $multiple = false, $required = false);

    /**
     * @param bool $multiple
     * @param bool $required
     *
     * @return $this
     */
    public function addFloatProperty(string $machine_name, string $name, $multiple = false, $required = false);

    /**
     * @param bool $multiple
     * @param bool $required
     *
     * @return $this
     */
    public function addStringProperty(string $machine_name, string $name, $multiple = false, $required = false);

    /**
     * @param bool $multiple
     * @param bool $required
     *
     * @return $this
     */
    public function addObjectProperty(string $machine_name, string $name, $multiple = false, $required = false);
}
