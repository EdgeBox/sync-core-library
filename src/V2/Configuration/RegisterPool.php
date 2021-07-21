<?php

namespace EdgeBox\SyncCore\V2\Configuration;

use EdgeBox\SyncCore\Interfaces\Configuration\IRegisterPool;
use EdgeBox\SyncCore\V2\BatchOperation;
use EdgeBox\SyncCore\V2\Raw\Model\CreatePoolDto;
use EdgeBox\SyncCore\V2\SyncCore;

class RegisterPool extends BatchOperation implements IRegisterPool
{
    /**
     * @var CreatePoolDto
     */
    protected $dto;

    /**
     * RegisterPool constructor.
     */
    public function __construct(SyncCore $core, string $pool_machine_name, string $pool_name)
    {
        $dto = new CreatePoolDto();
        $dto->setName($pool_name);
        $dto->setMachineName($pool_machine_name);

        parent::__construct(
            $core,
            BatchOperation::REQUEST_POOL_CREATE,
            $dto
        );
    }
}
