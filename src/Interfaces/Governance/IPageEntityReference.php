<?php

namespace EdgeBox\SyncCore\Interfaces\Governance;

/**
 * The thing a site holds the page as, named the way that site names it.
 *
 * A trigger names its page three times over. The key the Sync Core files it
 * under names nothing a site can resolve, the URL is what a person and a lookup
 * both go by, and this is the one a site already has a row for: the same three
 * values the page's figures are keyed on, read off the same stored reference,
 * so what a site files from a trigger lands beside what it filed from those.
 *
 * This library knows no content management system of its own, so the three
 * values are the site's own and are handed on as they stand.
 */
interface IPageEntityReference
{
    /**
     * The machine name of the kind of thing the site holds the page as.
     *
     * It is what an entity reference of this library calls the type, never the
     * bundle, and it is the value the page's figures are keyed on as their
     * `entity_type`, so a row filed from a trigger and a row filed from those
     * carry the same one.
     *
     * @return string
     */
    public function getEntityType();

    /**
     * @return string the site's own uuid of that thing, which is the only id of it this trigger carries
     */
    public function getRemoteUuid();

    /**
     * @return string the langcode the site files that row under
     */
    public function getLangcode();
}
