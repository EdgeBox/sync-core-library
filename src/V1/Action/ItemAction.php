<?php

namespace EdgeBox\SyncCore\V1\Action;

use EdgeBox\SyncCore\V1\Query\ItemQuery;
use EdgeBox\SyncCore\V1\Storage\Storage;

/**
 * Class ItemAction.
 *
 * Execute an action for a specific entity.
 */
class ItemAction extends ItemQuery
{
    protected $actionPath;

    protected $method;

    /**
     * ItemAction constructor.
     *
     * @param string $actionPath
     * @param string $method
     */
    public function __construct(Storage $storage, $actionPath, $method)
    {
        parent::__construct($storage);

        $this->actionPath = $actionPath;
        $this->method = $method;
    }

    /**
     * {@inheritdoc}
     */
    public function getPath()
    {
        return parent::getPath().'/'.$this->actionPath;
    }

    /**
     * {@inheritdoc}
     */
    public function getMethod()
    {
        return $this->method;
    }
}
