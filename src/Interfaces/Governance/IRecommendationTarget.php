<?php

namespace EdgeBox\SyncCore\Interfaces\Governance;

use EdgeBox\SyncCore\V2\Raw\Model\ContentRecommendationLinkTier;
use EdgeBox\SyncCore\V2\Raw\Model\ContentRecommendationTargetState;

/**
 * Where the page the trigger is about stands on the recommendation.
 *
 * A recommendation names the pages it is about; this is the one reading that
 * belongs to the page receiving the trigger, and it is absent when the page is
 * not among them.
 */
interface IRecommendationTarget
{
    /**
     * Whether the page already carries what the recommendation asks for.
     *
     * @see ContentRecommendationTargetState for the states a page can be in
     *
     * @return string
     */
    public function getState();

    /**
     * How the page is reached today.
     *
     * @see ContentRecommendationLinkTier for the tiers, from the strongest down
     *
     * @return string
     */
    public function getLinkTier();

    /**
     * @return ITargetEvidenceItem[] the readings of the page behind that state
     */
    public function getEvidence();
}
