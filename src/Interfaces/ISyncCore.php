<?php

namespace SyncCore\Interfaces;

/**
 * Interface ISyncCore.
 *
 * The interface both the 1.x Sync Core and the 2.x Sync Core must provide.
 *
 * @package Drupal\cms_content_sync\SyncCore\Interfaces
 */
interface ISyncCore {

  /**
   * @return IReportingService
   */
  public function getReportingService();

  /**
   * @return \Drupal\cms_content_sync\SyncCore\Interfaces\Syndication\ISyndicationService
   */
  public function getSyndicationService();

  /**
   * @return \Drupal\cms_content_sync\SyncCore\Interfaces\Configuration\IConfigurationService
   */
  public function getConfigurationService();

  /**
   * @return IBatch
   */
  public function batch();

  /**
   * @return bool
   */
  public function canHandleFlowConfigurationIndependently();

  /**
   * Whether the Sync Core allows to be called by clients directly for the pull
   * dashboard.
   *
   * @param bool|null $set
   *
   * @return bool|null
   */
  public function isDirectUserAccessEnabled($set = NULL);

  /**
   * @param string $id
   *   Optional ID of the site. Defaults to the ID of the
   *   current site.
   *
   * @return string
   */
  public function getSiteName($id = NULL);

  /**
   * @param bool $set
   */
  public function setSiteName($set);

  /**
   * @return string
   *
   * @throws \Drupal\cms_content_sync\SyncCore\Exception\NotFoundException
   */
  public function verifySiteId();

  /**
   * @param bool $force
   *   Force updating any existing configuration (skip
   *   verification from above).
   *
   * @return string
   *
   * @throws \Drupal\cms_content_sync\SyncCore\Exception\SiteVerificationFailedException
   */
  public function registerSite($force = FALSE);

  /**
   * Get a list of all sites from this pool that use a different version ID and
   * provide a diff on field basis.
   *
   * @param string $pool_id
   * @param string $entity_type
   * @param string $bundle
   * @param string $target_version
   *
   * @return array['SITE_ID']['remote_missing' | 'local_missing'][] = 'PROPERTY_NAME'
   */
  public function getSitesWithDifferentEntityTypeVersion($pool_id, $entity_type, $bundle, $target_version);

  /**
   * @return string[]
   */
  public function getReservedPropertyNames();

}
