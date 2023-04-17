<?php

namespace EdgeBox\SyncCore\Interfaces\Configuration;

interface IDefineStringProperty extends IDefineProperty
{
    /**
     * The given string must have at least this many characters.
     * Assuming UTF-8.
     *
     * @return $this
     */
    public function setMinLength(int $minLength);

    /**
     * The given string must have no more than this many characters.
     * Assuming UTF-8.
     *
     * @return $this
     */
    public function setMaxLength(int $maxLength);

    /**
     * Provide the encoding of the string if it's not simple UTF-8 or binary.
     * e.g. if it's URL-encoded or base64-encoded.
     *
     * @see RemoteEntityTypePropertyEncoding
     *
     * @return $this
     */
    public function setEncoding(string $encoding);

    /**
     * Only allow values that match the given pattern. Input must be a valid
     * PHP regular expression that we can extract the actual pattern and
     * flags/modifiers from.
     *
     * @return $this
     */
    public function setRegularExpressionFormat(string $pattern);
}
