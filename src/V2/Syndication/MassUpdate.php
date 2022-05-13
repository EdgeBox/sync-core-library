<?php

namespace EdgeBox\SyncCore\V2\Syndication;

use EdgeBox\SyncCore\Exception\InternalContentSyncError;
use EdgeBox\SyncCore\Interfaces\IApplicationInterface;
use EdgeBox\SyncCore\V2\Raw\Model\CreateMigrationDto;
use EdgeBox\SyncCore\V2\Raw\Model\EntityTypeVersionReference;
use EdgeBox\SyncCore\V2\Raw\Model\MigrationEntity;
use EdgeBox\SyncCore\V2\Raw\Model\MigrationSummary;
use EdgeBox\SyncCore\V2\Raw\Model\MigrationType;
use EdgeBox\SyncCore\V2\Raw\Model\PagedMigrationList;
use EdgeBox\SyncCore\V2\SyncCore;

abstract class MassUpdate
{
    /**
     * @var SyncCore
     */
    protected $core;

    /**
     * @var string
     */
    protected $namespaceMachineName;

    /**
     * @var string
     */
    protected $machineName;

    /**
     * @var string
     */
    protected $versionId;

    /**
     * @var string
     */
    protected $flow;

    /**
     * @var bool
     */
    protected $initial;

    /**
     * @var bool
     */
    protected $includeOtherSites;

    /**
     * @var MigrationEntity[]
     */
    protected $dtos;

    /**
     * @var MigrationSummary[]
     */
    protected $summaryDtos;

    /**
     * @var array
     */
    protected $summary;

    /**
     * @var string
     */
    protected $migrationId;

    /**
     * PullAll constructor.
     */
    public function __construct(SyncCore $core)
    {
        $this->core = $core;
    }

    public function withFlow(string $flow_id)
    {
        $this->flow = $flow_id;

        return $this;
    }

    public function getFlow(): ?string
    {
        return $this->flow;
    }

    public function withNamespaceMachineName(string $entity_type_name)
    {
        $this->namespaceMachineName = $entity_type_name;

        return $this;
    }

    public function getNamespaceMachineName(): ?string
    {
        return $this->namespaceMachineName;
    }

    public function withEntityTypeMachineName(string $bundle_name)
    {
        $this->machineName = $bundle_name;

        return $this;
    }

    public function getEntityTypeMachineName(): ?string
    {
        return $this->machineName;
    }

    public function withEntityTypeVersion(string $version_id)
    {
        $this->versionId = $version_id;

        return $this;
    }

    public function getEntityTypeVersion(): ?string
    {
        return $this->versionId;
    }

    public function isInitialMigration(bool $is_initial)
    {
        $this->initial = $is_initial;

        return $this;
    }

    public function includeOtherSites(?bool $set): bool
    {
        if (true === $set || false === $set) {
            $this->includeOtherSites = $set;
        }

        return (bool) $this->includeOtherSites;
    }

    public function getByStatus()
    {
        if (!$this->summary) {
            $this->getDtos();
            $this->updateSummary();
        }

        return $this->summary;
    }

    public function total()
    {
        $total = 0;
        foreach ($this->getByStatus() as $status => $count) {
            $total += $count;
        }

        return $total;
    }

    public function progress()
    {
        $done = ['finished', 'failed', 'aborted', 'limit-exceeded'];
        $total = 0;
        foreach ($this->getByStatus() as $status => $count) {
            if (!in_array($status, $done)) {
                continue;
            }
            $total += $count;
        }

        return $total;
    }

    /**
     * Start the migration with the given filters/settings and the provided type
     * argument.
     */
    public function executeWithType(string $type)
    {
        if (!$this->flow) {
            throw new InternalContentSyncError('Flow is required.');
        }
        if (!$this->namespaceMachineName) {
            throw new InternalContentSyncError('Namespace machine name is required.');
        }
        if (!$this->machineName) {
            throw new InternalContentSyncError('Entity type machine name is required.');
        }
        if (!$this->versionId) {
            throw new InternalContentSyncError('Entity type version is required.');
        }

        $migrationDto = new CreateMigrationDto();
        if (MigrationType::PUSH_ALL === $type && $this->initial) {
            $migrationDto->setSkipSyndication(true);
        }
        /**
         * @var MigrationType $type
         */
        $migrationDto->setType($type);
        $migrationDto->setInitialSetup($this->initial);
        $migrationDto->setFlowMachineName($this->flow);
        $entityType = new EntityTypeVersionReference();
        $entityType->setNamespaceMachineName($this->namespaceMachineName);
        $entityType->setMachineName($this->machineName);
        $entityType->setVersionId($this->versionId);
        $migrationDto->setEntityTypeReference($entityType);

        $request = $this->core->getClient()->migrationControllerCreateRequest($migrationDto);
        $response = $this->core->sendToSyncCoreAndExpect($request, MigrationEntity::class, IApplicationInterface::SYNC_CORE_PERMISSIONS_CONFIGURATION);
        $this->migrationId = $response->getId();
        $this->dtos = [$response];

        return $this;
    }

    protected function updateSummary()
    {
        $this->summary = [];
        foreach ($this->summaryDtos as $dto) {
            $by_status = $dto->getByStatus();
            ksort($by_status);
            foreach ($by_status as $status_count) {
                /**
                 * @var string $status
                 */
                $status = $status_count->getStatus();
                $status = preg_replace('@^[0-9]+-@', '', $status);
                $count = $status_count->getCount();
                if (!isset($this->summary[$status])) {
                    $this->summary[$status] = 0;
                }
                $this->summary[$status] += $count ?? 0;
            }
        }
    }

    /**
     * Request all DTOs to get the summary from. Result can be based on one or
     * multiple migrations, based on the filters that were provided.
     */
    abstract protected function getDtos();

    protected function getDtosWithTypes(array $types)
    {
        // Only have to get the summary DTO in this case.
        if (empty($this->dtos)) {
            $site_uuid = $this->includeOtherSites ? null : $this->core->getApplication()->getSiteUuid();
            $page = 0;
            $number_of_pages = 1;
            $dtos = [];
            do {
                $request = $this->core->getClient()->migrationControllerListRequest(
                    'true',
                    $this->machineName,
                    $this->namespaceMachineName,
                    $this->flow,
                    null,
                    join(',', $types),
                    $site_uuid,
                    null,
                    $this->initial ? 'true' : 'false',
                    $page,
                    25
                );
                $response = $this->core->sendToSyncCoreAndExpect($request, PagedMigrationList::class, IApplicationInterface::SYNC_CORE_PERMISSIONS_CONFIGURATION);
                $dtos = array_merge($dtos, $response->getItems());
                $number_of_pages = $response->getNumberOfPages();
                ++$page;
            } while ($page < $number_of_pages);

            $this->dtos = $dtos;
        }

        $this->summaryDtos = [];
        foreach ($this->dtos as $dto) {
            $request = $this->core->getClient()->migrationControllerSummaryRequest($dto->getId());
            $response = $this->core->sendToSyncCoreAndExpect($request, MigrationSummary::class, IApplicationInterface::SYNC_CORE_PERMISSIONS_CONFIGURATION);
            $this->summaryDtos[] = $response;
        }
    }
}
