<?php

namespace EdgeBox\SyncCore\V2\Embed;

use EdgeBox\SyncCore\V2\SyncCore;

// TODO: Library: Add interfaces.
class EmbedService {
  /**
   * @var SyncCore $core
   */
  protected $core;

  /**
   * EmbedService constructor.
   * @param SyncCore $core
   */
  public function __construct(SyncCore $core) {
    $this->core = $core;
  }
  
  public function registerSite($params) {
    return new RegisterSiteEmbed($this->core, $params);
  }
  public function siteRegistered($params) {
    return new SiteRegisteredEmbed($this->core);
  }
  public function pullDashboard($params) {
    return new PullDashboardEmbed($this->core, $params);
  }
}
