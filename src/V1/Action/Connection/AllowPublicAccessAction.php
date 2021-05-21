<?php

namespace EdgeBox\SyncCore\V1\Action\Connection;

use EdgeBox\SyncCore\V1\Action\ItemAction;
use EdgeBox\SyncCore\V1\Storage\PreviewEntityStorage;
use EdgeBox\SyncCore\V1\Storage\Storage;
use EdgeBox\SyncCore\V1\SyncCoreClient;

/**
 * Class AllowPublicAccessAction.
 *
 * Set whether or not to allow direct Sync Core communication.
 */
class AllowPublicAccessAction extends ItemAction
{
    /**
     * @var bool|null
     */
    protected $value = null;

    /**
     * CloneAction constructor.
     */
    public function __construct(Storage $storage)
    {
        parent::__construct($storage, '', SyncCoreClient::METHOD_POST);
    }

    /**
     * {@inheritdoc}
     */
    public function getPath()
    {
        return '/'.PreviewEntityStorage::EXTERNAL_PREVIEW_PATH.'/_allowPublicAccess';
    }

    /**
     * @param bool $set
     *
     * @return AllowPublicAccessAction
     */
    public function setAllowPublicAccess($set)
    {
        $this->value = $set;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getBody()
    {
        return $this->value;
    }

    /**
     * {@inheritdoc}
     */
    public function returnBoolean()
    {
        return 'success';
    }
}
