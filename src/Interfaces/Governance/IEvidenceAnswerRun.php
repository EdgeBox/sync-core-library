<?php

namespace EdgeBox\SyncCore\Interfaces\Governance;

/**
 * One answer an engine gave that the recommendation was read out of.
 */
interface IEvidenceAnswerRun
{
    /**
     * @return string the id the answer is held under
     */
    public function getId();

    /**
     * @return INamedReference the engine that answered
     */
    public function getEngine();

    /**
     * @return null|string the prompt it answered
     */
    public function getPromptText();

    /**
     * @return string the ISO 8601 timestamp it answered at
     */
    public function getAnsweredAt();
}
