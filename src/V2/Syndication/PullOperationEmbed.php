<?php

namespace EdgeBox\SyncCore\V2\Syndication;

use EdgeBox\SyncCore\Interfaces\Syndication\IEntityReference;
use EdgeBox\SyncCore\V2\Raw\Model\RemoteEntityDependency;
use EdgeBox\SyncCore\V2\Raw\Model\RemoteEntityEmbed;
use EdgeBox\SyncCore\V2\Raw\Model\RemoteEntityEmbedDraft;
use EdgeBox\SyncCore\V2\SyncCore;

class PullOperationEmbed implements IEntityReference
{
    /**
     * @var SyncCore
     */
    protected $core;
    /**
     * @var RemoteEntityDependency
     */
    protected $dto;
    /**
     * @var PullOperation
     */
    protected $pullOperation;
    /**
     * @var RemoteEntityEmbed|RemoteEntityEmbedDraft|null
     */
    protected $embed;
    /**
     * @var int|null
     */
    protected $embedIndex;

    /**
     * constructor.
     */
    public function __construct(SyncCore $core, RemoteEntityDependency $dto, PullOperation $pullOperation, ?int $embedIndex, $embed = null)
    {
        $this->core = $core;
        $this->dto = $dto;
        $this->pullOperation = $pullOperation;
        $this->embed = $embed;
        $this->embedIndex = $embedIndex;
    }

    /**
     * {@inheritdoc}
     */
    public function getDetails()
    {
        /**
         * @var array|null $details
         */
        $details = $this->dto->getReferenceDetails();

        if (!$details) {
            return [];
        }

        // Turn objects into arrays.
        return json_decode(json_encode($details), true);
    }

    /**
     * {@inheritdoc}
     */
    public function getId()
    {
        return $this->dto->getRemoteUniqueId();
    }

    /**
     * {@inheritdoc}
     */
    public function getUuid()
    {
        return $this->dto->getRemoteUuid();
    }

    /**
     * {@inheritdoc}
     */
    public function getType()
    {
        return $this->dto->getEntityTypeNamespaceMachineName();
    }

    /**
     * {@inheritdoc}
     */
    public function getBundle()
    {
        return $this->dto->getEntityTypeMachineName();
    }

    /**
     * {@inheritdoc}
     */
    public function getVersion()
    {
        return $this->dto->getEntityTypeVersion();
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return $this->dto->getName();
    }

    /**
     * {@inheritdoc}
     */
    public function getPoolIds()
    {
        return $this->dto->getPoolMachineNames();
    }

    /**
     * {@inheritdoc}
     */
    public function isEmbedded()
    {
        return (bool) $this->embed;
    }

    /**
     * {@inheritdoc}
     */
    public function getEmbeddedEntity()
    {
        $this->pullOperation->embedProcessed($this->embedIndex);

        return new PullOperation(
        $this->core,
        $this->embed,
        false,
        $this->pullOperation);
    }
}
