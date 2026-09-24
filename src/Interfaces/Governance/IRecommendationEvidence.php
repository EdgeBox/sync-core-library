<?php

namespace EdgeBox\SyncCore\Interfaces\Governance;

/**
 * Why the recommendation exists: what the engines were asked, how often the
 * subject came up, and where the answers sent people instead of this site.
 *
 * Every figure arrives with the count it was measured over, so a site shows a
 * rate against its ground rather than on its own. The sender sheds this part
 * first when a trigger is over the size a site accepts, so a recommendation can
 * arrive without it.
 */
interface IRecommendationEvidence
{
    /**
     * @return null|string the key of the cluster of related prompts the recommendation was read out of
     */
    public function getClusterKey();

    /**
     * @return float 0..1 — how much of the ground raised the subject without being prompted for it
     */
    public function getUnpromptedRate();

    /**
     * @return int the answers the unprompted rate was measured over
     */
    public function getUnpromptedDenominator();

    /**
     * @return float 0..1 — how much of the ground answered without this site
     */
    public function getGapRate();

    /**
     * @return int the answers the gap rate was measured over
     */
    public function getGapDenominator();

    /**
     * @return INamedReference[] the sources those answers cited instead of this site
     */
    public function getGapSources();

    /**
     * @return IEvidenceAnswerRun[] the answers themselves, shed before the rest of this part is
     */
    public function getRuns();

    /**
     * @return IEvidenceCoverage[] how many answers each engine gave for each purpose
     */
    public function getCoverage();
}
