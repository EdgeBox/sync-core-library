<?php

namespace EdgeBox\SyncCore\V1\Entity;

use EdgeBox\SyncCore\V1\Action\ConnectionSynchronization\SynchronizeAllAction;
use EdgeBox\SyncCore\V1\Action\ConnectionSynchronization\SynchronizeAllStatus;
use EdgeBox\SyncCore\V1\Action\ConnectionSynchronization\SynchronizeSingleAction;

class ConnectionSynchronization extends Entity
{
    /**
     * Create and return an instance of an SynchronizeSingleAction.
     *
     * @param string $item_id
     *
     * @return \EdgeBox\SyncCore\V1\Action\ConnectionSynchronization\SynchronizeSingleAction
     */
    public function synchronizeSingle($item_id)
    {
        $action = new SynchronizeSingleAction($this->storage);
        $action->setItemId($item_id);
        $action->setEntityId($this->id);

        return $action;
    }

    /**
     * Create and return an instance of an SynchronizeAllStatus.
     *
     * @param string $runner_id
     *
     * @return \EdgeBox\SyncCore\V1\Action\ConnectionSynchronization\SynchronizeAllStatus
     */
    public function synchronizeAllStatus($runner_id)
    {
        $action = new SynchronizeAllStatus($this->storage);
        $action->setItemId($runner_id);
        $action->setEntityId($this->id);

        return $action;
    }

    /**
     * Create and return an instance of an SynchronizeAllAction.
     *
     * @return \EdgeBox\SyncCore\V1\Action\ConnectionSynchronization\SynchronizeAllAction
     */
    public function synchronizeAll()
    {
        $action = new SynchronizeAllAction($this->storage);
        $action->setEntityId($this->id);

        return $action;
    }
}
