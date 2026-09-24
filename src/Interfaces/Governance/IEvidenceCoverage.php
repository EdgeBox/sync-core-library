<?php

namespace EdgeBox\SyncCore\Interfaces\Governance;

/**
 * How many answers one engine gave for one purpose, so a figure measured over
 * them can be read against the ground it was measured on.
 */
interface IEvidenceCoverage
{
    /**
     * @return INamedReference the engine that answered
     */
    public function getEngine();

    /**
     * @return INamedReference what the prompts behind those answers were asked for
     */
    public function getPurpose();

    /**
     * @return int how many answers that pairing produced
     */
    public function getAnswers();
}
