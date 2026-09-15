<?php

namespace EdgeBox\SyncCore\V2\Governance;

use EdgeBox\SyncCore\Interfaces\Governance\ActingUser;
use EdgeBox\SyncCore\Interfaces\Governance\IPostExternalDraft;
use EdgeBox\SyncCore\Interfaces\IApplicationInterface;
use EdgeBox\SyncCore\V2\Raw\Model\ContentOptimizationEntity;
use EdgeBox\SyncCore\V2\Raw\Model\ExternalContentOptimizationDto;
use EdgeBox\SyncCore\V2\Raw\ObjectSerializer;
use EdgeBox\SyncCore\V2\SyncCore;

/**
 * Posts an externally authored draft for a content item, optionally against an
 * existing optimization, and reports whether the post created a new record or
 * matched an idempotent repeat.
 */
class PostExternalDraft implements IPostExternalDraft
{
    /**
     * @var SyncCore
     */
    protected $core;

    /**
     * @var string
     */
    protected $contentItemKey;

    /**
     * @var string
     */
    protected $externalRevisionId;

    /**
     * @var string
     */
    protected $renderedHtml;

    /**
     * @var null|ActingUser
     */
    protected $as;

    /**
     * @var null|string
     */
    protected $optimizationId;

    /**
     * @var null|string[]
     */
    protected $optimizationTypeKeys;

    /**
     * @var null|string[]
     */
    protected $sourceIssueKeys;

    public function __construct(SyncCore $core, string $content_item_key, string $external_revision_id, string $rendered_html, ?ActingUser $as = null)
    {
        $this->core = $core;
        $this->contentItemKey = $content_item_key;
        $this->externalRevisionId = $external_revision_id;
        $this->renderedHtml = $rendered_html;
        $this->as = $as;
    }

    public function forOptimization(string $optimization_id)
    {
        $this->optimizationId = $optimization_id;

        return $this;
    }

    public function withOptimizationTypeKeys(array $keys)
    {
        $this->optimizationTypeKeys = array_values($keys);

        return $this;
    }

    public function fixingIssues(array $issue_keys)
    {
        $this->sourceIssueKeys = array_values($issue_keys);

        return $this;
    }

    public function execute()
    {
        $dto = new ExternalContentOptimizationDto();
        $dto->setContentItemKey($this->contentItemKey);
        $dto->setExternalRevisionId($this->externalRevisionId);
        $dto->setRenderedHtml($this->renderedHtml);
        if (null !== $this->optimizationId) {
            $dto->setOptimizationId($this->optimizationId);
        }
        if (null !== $this->optimizationTypeKeys) {
            $dto->setOptimizationTypeKeys($this->optimizationTypeKeys);
        }
        if (null !== $this->sourceIssueKeys) {
            $dto->setSourceIssueKeys($this->sourceIssueKeys);
        }

        $request = $this->core->getClient()->contentOptimizationControllerCreateExternalRecordRequest($dto);

        $response = $this->core->sendToSyncCoreAsUserForResponse(
            $request,
            $this->as,
            IApplicationInterface::SYNC_CORE_PERMISSIONS_CONTENT,
            SyncCore::PUSH_RETRY_COUNT
        );

        $was_existing = 200 === $response->getStatusCode();

        /** @var ContentOptimizationEntity $optimization */
        $optimization = @ObjectSerializer::deserialize((string) $response->getBody(), ContentOptimizationEntity::class, []);

        return new ExternalDraftResult($optimization, $was_existing);
    }
}
