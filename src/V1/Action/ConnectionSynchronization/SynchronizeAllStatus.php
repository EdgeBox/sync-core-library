<?php

namespace EdgeBox\SyncCore\V1\Action\ConnectionSynchronization;

use EdgeBox\SyncCore\V1\Action\SubItemAction;
use EdgeBox\SyncCore\V1\Storage\Storage;
use EdgeBox\SyncCore\V1\SyncCoreClient;

/**
 * Class SynchronizeAllStatus.
 *
 * Get the current status of a SynchronizeAllAction.
 */
class SynchronizeAllStatus extends SubItemAction
{
    /**
     * SynchronizeAllStatus constructor.
     */
    public function __construct(Storage $storage)
    {
        parent::__construct($storage, 'synchronize', SyncCoreClient::METHOD_POST);
    }

    /**
     * {@inheritdoc}
     */
    public function getMethod()
    {
        return SyncCoreClient::METHOD_GET;
    }
}
