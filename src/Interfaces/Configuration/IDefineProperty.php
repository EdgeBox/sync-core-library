<?php

namespace EdgeBox\SyncCore\Interfaces\Configuration;

use EdgeBox\SyncCore\Interfaces\IBatchOperation;

interface IDefineProperty extends IBatchOperation
{
    /**
     * Restrict the values to a set of allowed values, defined one after anohter
     * through this function.
     *
     * @return $this
     */
    public function addAllowedValue(string $name, mixed $value = null);

    /**
     * Restrict values to a specific format e.g. expecting email addresses.
     *
     * @see RemoteEntityTypePropertyFormat
     *
     * @return $this
     */
    public function setFormat(string $format);

    /**
     * Require at least this many items.
     * Only allowed if $multiple is set to TRUE.
     *
     * @return $this
     */
    public function setMinItems(int $min);

    /**
     * Don't allow more than this many items.
     * Only allowed if $multiple is set to TRUE.
     *
     * @return $this
     */
    public function setMaxItems(int $min);

    /**
     * Whether or not this property can be translated.
     *
     * @return $this
     */
    public function setLocalized(bool $set);

    /**
     * Whether or not this property is shared across the namespace.
     * E.g. in Drupal, the "label" property is shared across all bundles of an
     * entity type.
     *
     * @return $this
     */
    public function setShared(bool $set);
}
