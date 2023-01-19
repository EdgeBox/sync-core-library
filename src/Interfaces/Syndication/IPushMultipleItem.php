<?php

namespace EdgeBox\SyncCore\Interfaces\Syndication;

interface IPushMultipleItem
{
    /**
     * Add a Pool to the entity. Must provide at least one.
     *
     * @return $this
     */
    public function addPool(string $pool_machine_name);

    /**
     * Add a Pool to the entity. Must provide at least one.
     *
     * @param string $pool_machine_name
     *
     * @return $this
     */
    public function addTranslation(string $language_id, string $view_url);

    /**
     * @return $this
     */
    public function setName(string $value);

    /**
     * @return $this
     */
    public function setSourceDeepLink(string $value);

    /**
     * @return $this
     */
    public function isDeleted(bool $is);

    /**
     * @return $this
     */
    public function isSource(bool $is);

    /**
     * Get the serialized entity as it is sent to the Sync Core. Only needed for
     * debugging purpooses.
     *
     * @return mixed
     */
    public function getData();
}
