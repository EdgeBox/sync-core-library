<?php

namespace EdgeBox\SyncCore\V1\Action\ConnectionSynchronization;

use EdgeBox\SyncCore\V1\Action\ItemAction;
use EdgeBox\SyncCore\V1\Storage\Storage;
use EdgeBox\SyncCore\V1\SyncCoreClient;

/**
 * Class SynchronizeAllAction.
 *
 * Trigger synchronization for a specific entity.
 */
class SynchronizeAllAction extends ItemAction
{
    /**
     * SynchronizeAllAction constructor.
     */
    public function __construct(Storage $storage)
    {
        parent::__construct($storage, 'synchronize', SyncCoreClient::METHOD_POST);

        $this->arguments['update_all'] = 'true';
    }

    /**
     * @param bool $set
     *
     * @return $this
     */
    public function force($set)
    {
        $this->arguments['force'] = $set ? 'true' : 'false';

        return $this;
    }
}
