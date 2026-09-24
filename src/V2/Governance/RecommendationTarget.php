<?php

namespace EdgeBox\SyncCore\V2\Governance;

use EdgeBox\SyncCore\Interfaces\Governance\IRecommendationTarget;

class RecommendationTarget implements IRecommendationTarget
{
    /**
     * @var array
     */
    protected $data;

    public function __construct(array $data)
    {
        $this->data = $data;
    }

    public function getState()
    {
        return (string) ($this->data['state'] ?? '');
    }

    public function getLinkTier()
    {
        return (string) ($this->data['linkTier'] ?? '');
    }

    public function getEvidence()
    {
        $evidence = [];
        $list = $this->data['evidence'] ?? null;
        foreach (is_array($list) ? $list : [] as $item) {
            if (is_array($item)) {
                $evidence[] = new TargetEvidenceItem($item);
            }
        }

        return $evidence;
    }
}
