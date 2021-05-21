<?php

namespace EdgeBox\SyncCore\V2;

use ArrayAccess;
use EdgeBox\SyncCore\Exception\InternalContentSyncError;
use EdgeBox\SyncCore\Exception\SyncCoreException;
use EdgeBox\SyncCore\Interfaces\IApplicationInterface;
use EdgeBox\SyncCore\Interfaces\IBatch;
use EdgeBox\SyncCore\Interfaces\IBatchOperation;
use EdgeBox\SyncCore\V2\Raw\Model\ModelInterface;
use JsonSerializable;

class BatchOperation extends SerializableWithSyncCoreReference implements IBatchOperation
{
    /**
     * @var string|null
     */
    protected $requestMethod;

    /**
     * @var ModelInterface|ArrayAccess|JsonSerializable|null
     */
    protected $dto;

    /**
     * Batchable constructor.
     *
     * @param ModelInterface|ArrayAccess|JsonSerializable|null $dto
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

    public const REQUEST_FLOW_CREATE = 'flowControllerCreateRequest';
    public const REQUEST_POOL_CREATE = 'poolControllerCreateRequest';
    public const REQUEST_ENTITY_TYPE_CREATE = 'remoteEntityTypeVersionControllerCreateRequest';

    /**
     * @throws SyncCoreException
     */
    public function execute()
    {
        if (!$this->requestMethod || !$this->dto) {
            return;
        }

        $request_method = $this->requestMethod;
        $dto = $this->dto;
        if (self::REQUEST_FLOW_CREATE === $request_method) {
            $request = $this->core->getClient()->flowControllerCreateRequest($dto);
        } elseif (self::REQUEST_POOL_CREATE === $request_method) {
            $request = $this->core->getClient()->poolControllerCreateRequest($dto);
        } elseif (self::REQUEST_ENTITY_TYPE_CREATE === $request_method) {
            $request = $this->core->getClient()->remoteEntityTypeVersionControllerCreateRequest($dto);
        } else {
            throw new InternalContentSyncError("Using unknown request method $request_method.");
        }

        $this
      ->core
      ->sendToSyncCore($request, IApplicationInterface::SYNC_CORE_PERMISSIONS_CONFIGURATION);
    }

    /**
     * @return string
     */
    public function serialize()
    {
        return serialize([
            'syncCore' => $this->serializeSyncCore(),
            'requestMethod' => $this->requestMethod,
            'dtoClass' => get_class($this->dto),
            'dtoSerialized' => $this->dto->jsonSerialize(),
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

        $this->dto = new $data['dtoClass']($data['dtoSerialized']);
    }
}
