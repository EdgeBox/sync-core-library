<?php

namespace EdgeBox\SyncCore\V1\Entity;

use Drupal\cms_content_sync\SyncCore\V1\Action\Connection\LoginAction;

/**
 *
 */
class Connection extends Entity {

  /**
   * Create and return an instance of an LoginAction.
   *
   * @return \Drupal\cms_content_sync\SyncCore\V1\Action\Connection\LoginAction
   */
  public function login() {
    $action = new LoginAction($this->storage);
    $action->setEntityId($this->id);

    return $action;
  }

}
