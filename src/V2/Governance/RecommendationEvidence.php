<?php

namespace EdgeBox\SyncCore\V2\Governance;

use EdgeBox\SyncCore\Interfaces\Governance\IRecommendationEvidence;

class RecommendationEvidence implements IRecommendationEvidence
{
    /**
     * @var array
     */
    protected $data;

    public function __construct(array $data)
    {
        $this->data = $data;
    }

    public function getClusterKey()
    {
        return $this->data['clusterKey'] ?? null;
    }

    public function getUnpromptedRate()
    {
        return (float) ($this->data['unpromptedRate'] ?? 0);
    }

    public function getUnpromptedDenominator()
    {
        return (int) ($this->data['unpromptedDenominator'] ?? 0);
    }

    public function getGapRate()
    {
        return (float) ($this->data['gapRate'] ?? 0);
    }

    public function getGapDenominator()
    {
        return (int) ($this->data['gapDenominator'] ?? 0);
    }

    public function getGapSources()
    {
        $sources = [];
        $list = $this->data['gapSources'] ?? null;
        foreach (is_array($list) ? $list : [] as $source) {
            if (is_array($source)) {
                $sources[] = new NamedReference($source);
            }
        }

        return $sources;
    }

    public function getRuns()
    {
        $runs = [];
        $list = $this->data['runs'] ?? null;
        foreach (is_array($list) ? $list : [] as $run) {
            if (is_array($run)) {
                $runs[] = new EvidenceAnswerRun($run);
            }
        }

        return $runs;
    }

    public function getCoverage()
    {
        $coverage = [];
        $list = $this->data['coverage'] ?? null;
        foreach (is_array($list) ? $list : [] as $entry) {
            if (is_array($entry)) {
                $coverage[] = new EvidenceCoverage($entry);
            }
        }

        return $coverage;
    }
}
