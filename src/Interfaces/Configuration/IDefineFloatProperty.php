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
     * The given value fits within these many bytes.
     *
     * @return $this
     */
    public function setByteSize(int $byteCount);

    /**
     * Declare the unit that the value is measured in.
     *
     * @return $this
     */
    public function setUnit(string $unit);
}
