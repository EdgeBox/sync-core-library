<?php

namespace EdgeBox\SyncCore\Interfaces\Governance;

use EdgeBox\SyncCore\V2\Raw\Model\ContentOptimizationExternalOutcome;

/**
 * What became of a draft a site wrote for an optimization itself and posted
 * with postExternalDraft().
 *
 * Names the optimization, the draft by the same revision identifier the site
 * posted it with, and the outcome: the site published the draft, published
 * another version of the page instead or replaced the draft with a newer one,
 * or deleted the draft. Constructed with named arguments:
 *
 *     new ExternalDraftOutcome(
 *         optimizationId: $optimization_id,
 *         externalRevisionId: $revision_id,
 *         outcome: ContentOptimizationExternalOutcome::PUBLISHED,
 *     );
 */
final class ExternalDraftOutcome
{
    /**
     * @var string
     */
    private $optimizationId;

    /**
     * @var string
     */
    private $externalRevisionId;

    /**
     * @var string
     */
    private $outcome;

    /**
     * @param string $optimizationId     the optimization the draft was posted for
     * @param string $externalRevisionId the revision identifier the draft was posted with
     * @param string $outcome            one of the ContentOptimizationExternalOutcome constants
     */
    public function __construct(string $optimizationId, string $externalRevisionId, string $outcome)
    {
        $this->optimizationId = $optimizationId;
        $this->externalRevisionId = $externalRevisionId;
        $this->outcome = $outcome;
    }

    /**
     * @return string
     */
    public function getOptimizationId()
    {
        return $this->optimizationId;
    }

    /**
     * @return string
     */
    public function getExternalRevisionId()
    {
        return $this->externalRevisionId;
    }

    /**
     * @return string one of the ContentOptimizationExternalOutcome constants
     *
     * @see ContentOptimizationExternalOutcome
     */
    public function getOutcome()
    {
        return $this->outcome;
    }
}
