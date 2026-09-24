<?php

namespace EdgeBox\SyncCore\Interfaces\Governance;

use EdgeBox\SyncCore\V2\Raw\Model\AuthoredTitle;
use EdgeBox\SyncCore\V2\Raw\Model\TextFormat;

/**
 * One written part of a recommendation, together with who wrote it and when.
 *
 * The wording is written for the locale the recommendation names and is meant
 * to be read, verified and adapted rather than published as it arrives. Every
 * part below travels with it, so nothing here has to be guessed at.
 */
interface IAuthoredText
{
    /**
     * @return string the wording itself
     */
    public function getText();

    /**
     * The media type the wording is written in.
     *
     * @see TextFormat for the media types this library has names for
     *
     * @return string
     */
    public function getFormat();

    /**
     * Who wrote it.
     *
     * @see AuthoredTitle for the origins, the same ones on every written part
     *
     * @return string
     */
    public function getProvenance();

    /**
     * @return string the ISO 8601 timestamp the wording was written at
     */
    public function getAt();
}
