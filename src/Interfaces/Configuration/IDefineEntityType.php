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
    public function addBooleanProperty(string $name, $multiple = false, $required = false);

    /**
     * @param bool $multiple
     * @param bool $required
     *
     * @return $this
     */
    public function addIntegerProperty(string $name, $multiple = false, $required = false);

    /**
     * @param bool $multiple
     * @param bool $required
     *
     * @return $this
     */
    public function addFloatProperty(string $name, $multiple = false, $required = false);

    /**
     * @param bool $multiple
     * @param bool $required
     *
     * @return $this
     */
    public function addStringProperty(string $name, $multiple = false, $required = false);

    /**
     * @param bool $multiple
     * @param bool $required
     *
     * @return $this
     */
    public function addObjectProperty(string $name, $multiple = false, $required = false);
}
