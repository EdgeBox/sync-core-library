<?php

namespace EdgeBox\SyncCore\V2\Governance;

use EdgeBox\SyncCore\Interfaces\Governance\ActingUser;
use EdgeBox\SyncCore\Interfaces\Governance\ExternalDraftOutcome;
use EdgeBox\SyncCore\Interfaces\Governance\IReportExternalDraftOutcome;
use EdgeBox\SyncCore\Interfaces\IApplicationInterface;
use EdgeBox\SyncCore\V2\Raw\Model\ContentOptimizationEntity;
use EdgeBox\SyncCore\V2\Raw\Model\ExternalContentOptimizationOutcomeDto;
use EdgeBox\SyncCore\V2\Raw\ObjectSerializer;
use EdgeBox\SyncCore\V2\SyncCore;

/**
 * Reports what became of a draft a site wrote for an optimization itself, and
 * whether the report changed the optimization or matched a report it already
 * had.
 */
class ReportExternalDraftOutcome implements IReportExternalDraftOutcome
{
    /**
     * @var SyncCore
     */
    protected $core;

    /**
     * @var ExternalDraftOutcome
     */
    protected $outcome;

    /**
     * @var null|ActingUser
     */
    protected $as;

    public function __construct(SyncCore $core, ExternalDraftOutcome $outcome, ?ActingUser $as = null)
    {
        $this->core = $core;
        $this->outcome = $outcome;
        $this->as = $as;
    }

    public function execute()
    {
        $dto = new ExternalContentOptimizationOutcomeDto();
        $dto->setExternalRevisionId($this->outcome->getExternalRevisionId());

        /** @var mixed $outcome */
        $outcome = $this->outcome->getOutcome();
        $dto->setOutcome($outcome);

        $request = $this->core->getClient()->contentOptimizationControllerReportExternalOutcomeRequest($this->outcome->getOptimizationId(), $dto);

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
