<?php

namespace EdgeBox\SyncCore\V1\Query;

use EdgeBox\SyncCore\V1\SyncCoreClient;

/**
 * Class UpdateItemQuery
 * Update an existing item.
 */
class UpdateItemQuery extends ItemQuery
{
    /**
     * @var array|null
     */
    protected $item = null;

    /**
     * {@inheritdoc}
     */
    public static function create($storage)
    {
        return new UpdateItemQuery($storage);
    }

    /**
     * {@inheritdoc}
     */
    public function setAsDependency($set)
    {
        if ($set) {
            $this->arguments['is_dependency'] = 'true';
        } else {
            unset($this->arguments['is_dependency']);
        }

        return $this;
    }

    /**
     * @param array $item
     *
     * @return $this
     */
    public function setItem($item)
    {
        $this->item = $item;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getBody()
    {
        return $this->item;
    }

    /**
     * {@inheritdoc}
     */
    public function getMethod()
    {
        return SyncCoreClient::METHOD_PUT;
    }
}
