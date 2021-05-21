<?php

namespace EdgeBox\SyncCore\V1\Action;

/**
 * Class SubItemAction.
 *
 * Execute an action for a specific entity.
 */
class SubItemAction extends ItemAction
{
    protected $itemId = null;

    /**
     * {@inheritdoc}
     */
    public function getPath()
    {
        return parent::getPath().'/'.$this->itemId;
    }

    /**
     * Set the item ID for this action.
     *
     * @param string $itemId
     *
     * @return $this
     */
    public function setItemId($itemId)
    {
        $this->itemId = $itemId;

        return $this;
    }
}
