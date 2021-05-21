<?php

namespace EdgeBox\SyncCore\V1\Query;

use EdgeBox\SyncCore\V1\SyncCoreClient;

/**
 * Class DeleteItemQuery
 * Delete an existing item.
 */
class DeleteItemQuery extends ItemQuery
{
    /**
     * {@inheritdoc}
     */
    public static function create($storage)
    {
        return new DeleteItemQuery($storage);
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
     * {@inheritdoc}
     */
    public function getMethod()
    {
        return SyncCoreClient::METHOD_DELETE;
    }
}
