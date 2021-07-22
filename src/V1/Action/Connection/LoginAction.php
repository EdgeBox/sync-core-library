<?php

namespace EdgeBox\SyncCore\V1\Action\Connection;

use EdgeBox\SyncCore\V1\Action\ItemAction;
use EdgeBox\SyncCore\V1\Storage\Storage;
use EdgeBox\SyncCore\V1\SyncCoreClient;

/**
 * Class LoginAction.
 *
 * Trigger login for a specific connection.
 */
class LoginAction extends ItemAction
{
    /**
     * LoginAction constructor.
     */
    public function __construct(Storage $storage)
    {
        parent::__construct($storage, 'login', SyncCoreClient::METHOD_POST);
    }

    /**
     * @return string
     */
    public function getBody()
    {
        // POST must contain a body, but can be anything.
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function returnBoolean()
    {
        return 'success';
    }
}
