<?php

namespace EdgeBox\SyncCore\V2;

use ArrayAccess;
use EdgeBox\SyncCore\Exception\InternalContentSyncError;
use EdgeBox\SyncCore\Exception\SyncCoreException;
use EdgeBox\SyncCore\Interfaces\IApplicationInterface;
use EdgeBox\SyncCore\Interfaces\IBatch;
use EdgeBox\SyncCore\Interfaces\IBatchOperation;
use EdgeBox\SyncCore\V2\Raw\Model\ModelInterface;
use EdgeBox\SyncCore\V2\Raw\ObjectSerializer;
use JsonSerializable;

class BatchOperation extends SerializableWithSyncCoreReference implements IBatchOperation
{
    public const REQUEST_FLOW_CREATE = 'flowControllerCreateRequest';
    public const REQUEST_POOL_CREATE = 'poolControllerCreateRequest';
    public const REQUEST_ENTITY_TYPE_CREATE = 'remoteEntityTypeVersionControllerCreateRequest';
    /**
     * @var null|string
     */
    protected $requestMethod;

    /**
     * @var null|ArrayAccess|JsonSerializable|ModelInterface
     */
    protected $dto;

    /**
     * Batchable constructor.
     *
     * @param null|ArrayAccess|JsonSerializable|ModelInterface $dto
     */
    public function __construct(SyncCore $core, ?string $requestMethod, $dto)
    {
        parent::__construct($core);

        $this->requestMethod = $requestMethod;
        $this->dto = $dto;
    }

    /**
     * {@inheritdoc}
     */
    public function addToBatch(IBatch $batch)
    {
        $batch->add($this);

        return $this;
    }

    /**
     * @throws SyncCoreException
     */
    public function execute()
    {
        if (!$this->requestMethod || !$this->dto) {
            return;
        }

        $this->optimize();

        $request_method = $this->requestMethod;
        $dto = $this->dto;
        if (self::REQUEST_FLOW_CREATE === $request_method) {
            $request = $this->core->getClient()->flowControllerCreateRequest(createFlowDto: $dto);
        } elseif (self::REQUEST_POOL_CREATE === $request_method) {
            $request = $this->core->getClient()->poolControllerCreateRequest(createPoolDto: $dto);
        } elseif (self::REQUEST_ENTITY_TYPE_CREATE === $request_method) {
            $request = $this->core->getClient()->remoteEntityTypeVersionControllerCreateRequest(createRemoteEntityTypeVersionDto: $dto);
        } else {
            throw new InternalContentSyncError("Using unknown request method {$request_method}.");
        }

        $this
            ->core
            ->sendToSyncCore($request, IApplicationInterface::SYNC_CORE_PERMISSIONS_CONFIGURATION, false, SyncCore::CONFIG_EXPORT_RETRY_COUNT)
        ;
    }

    /**
     * @return string
     */
    public function serialize()
    {
        return serialize([
            'syncCore' => $this->serializeSyncCore(),
            'requestMethod' => $this->requestMethod,
            'dtoClass' => $this->dto ? get_class($this->dto) : null,
            'dtoSerialized' => $this->dto ? $this->dto->jsonSerialize() : null,
        ]);
    }

    /**
     * @param string $serialized
     */
    public function unserialize($serialized)
    {
        $data = unserialize($serialized);

        $this->unserializeSyncCore($data['syncCore']);

        $this->requestMethod = $data['requestMethod'];

        if ($data['dtoClass'] && $data['dtoSerialized']) {
            $this->dto = @ObjectSerializer::deserialize($data['dtoSerialized'], $data['dtoClass'], []);
        } else {
            $this->dto = null;
        }
    }

    /**
     * {@inheritDoc}
     */
    public function getSerializedDto()
    {
        $this->optimize();

        $dto = $this->dto;

        return ObjectSerializer::sanitizeForSerialization($dto);
    }

    /**
     * Optimize the configuration prior to sending it to the Sync Core.
     */
    protected function optimize()
    {
    }
}
