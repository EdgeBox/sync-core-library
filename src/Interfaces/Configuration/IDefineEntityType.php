<?php

namespace EdgeBox\SyncCore\Interfaces\Configuration;

use EdgeBox\SyncCore\Interfaces\IBatchOperation;

interface IDefineEntityType extends IBatchOperation
{
    /**
     * @param bool $set
     *
     * @return $this
     */
    public function isTranslatable($set);

    /**
     * @param bool $set
     *
     * @return $this
     */
    public function isFile($set);

    /**
     * @param string $name
     * @param bool   $multiple
     * @param bool   $required
     *
     * @return $this
     */
    public function addBooleanProperty($name, $multiple = false, $required = false);

    /**
     * @param string $name
     * @param bool   $multiple
     * @param bool   $required
     *
     * @return $this
     */
    public function addIntegerProperty($name, $multiple = false, $required = false);

    /**
     * @param string $name
     * @param bool   $multiple
     * @param bool   $required
     *
     * @return $this
     */
    public function addFloatProperty($name, $multiple = false, $required = false);

    /**
     * @param string $name
     * @param bool   $multiple
     * @param bool   $required
     *
     * @return $this
     */
    public function addStringProperty($name, $multiple = false, $required = false);

    /**
     * @param string $name
     * @param bool   $multiple
     * @param bool   $required
     *
     * @return $this
     */
    public function addObjectProperty($name, $multiple = false, $required = false);
}
