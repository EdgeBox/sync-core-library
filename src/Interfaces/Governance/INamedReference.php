<?php

namespace EdgeBox\SyncCore\Interfaces\Governance;

/**
 * Something the Sync Core holds, referred to by its key and its name at once.
 *
 * The sender resolves the name before it sends the reference, so a site renders
 * the reference as it stands and never looks a name up of its own.
 */
interface INamedReference
{
    /**
     * @return string the machine key
     */
    public function getKey();

    /**
     * @return string the name written to be shown to a person
     */
    public function getName();
}
