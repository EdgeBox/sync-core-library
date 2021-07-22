<?php

namespace EdgeBox\SyncCore\V1\Entity;

use EdgeBox\SyncCore\V1\Action\Connection\AllowPublicAccessAction;

class EntityPreviewConnection extends Connection
{
    /**
     * Create and return an instance of an SynchronizeSingleAction.
     *
     * @param bool $set
     *
     * @return \EdgeBox\SyncCore\V1\Action\Connection\AllowPublicAccessAction
     */
    public function allowPublicAccess($set)
    {
        $action = new AllowPublicAccessAction($this->storage);
        $action->setAllowPublicAccess($set);

        return $action;
    }
}
