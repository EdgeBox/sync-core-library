<?php

namespace EdgeBox\SyncCore\Interfaces\Configuration;

interface IDefineFloatProperty extends IDefineProperty
{
    /**
     * The given value must be at least this great.
     *
     * @return $this
     */
    public function setMinValue(float $minValue);

    /**
     * The given value must not be greater than this.
     *
     * @return $this
     */
    public function setMaxValue(float $maxValue);

    /**
     * Declare the unit that the value is measured in.
     *
     * @return $this
     */
    public function setUnit(string $unit);
}