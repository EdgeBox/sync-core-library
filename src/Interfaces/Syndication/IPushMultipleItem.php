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
     * Add a translation for the entity.
     *
     * @param string $language_id The ID of the language, e.g. "en" or "de".
     * @param string $view_url the deep link to the entity at this site
     * @param bool $changed Whether or not this translation has changed (i.e. requires an update).
     *
     * @return $this
     */
    public function addTranslation(string $language_id, string $view_url, bool $changed = true);

    /**
     * Delete a translation for the entity.
     *
     * @param string $language_id The ID of the language, e.g. "en" or "de".
     *
     * @return $this
     */
    public function deleteTranslation(string $language_id);

    /**
     * Whether or not the entity changed. If not and only changed translations are
     * requested, this is skipped.
     * Not allowed if the root entity has not been sent before at all.
     *
     * @return $this
     */
    public function hasChanged(bool $changed);

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
