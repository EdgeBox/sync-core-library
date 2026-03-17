<?php

namespace EdgeBox\SyncCore\Interfaces\Configuration;

interface IDefineTextProfileHtmlPolicy
{
    /**
     * @param mixed $set
     *
     * @return $this
     */
    public function setDialect($set);

    /**
     * @return $this
     */
    public function setRequireWellFormedHtml(bool $set);

    /**
     * @return $this
     */
    public function allowTag(string $tag);

    /**
     * @param null|string[] $allowed_values
     *
     * @return $this
     */
    public function allowAttribute(string $tag, string $attribute, ?array $allowed_values = null);

    /**
     * @return $this
     */
    public function allowAttributePattern(string $tag, string $attribute, string $allowed_pattern);

    /**
     * @return $this
     */
    public function setAllowStyleAttribute(bool $set);
}
