<?php

namespace EdgeBox\SyncCore\V1;

use EdgeBox\SyncCore\Interfaces\IReportingService;
use EdgeBox\SyncCore\V1\Query\SimpleQuery;

/**
 *
 */
class ReportingService implements IReportingService {

  /**
   * @var \Drupal\cms_content_sync\SyncCore\V1\SyncCore
   */
  protected $core;

  /**
   * SyndicationService constructor.
   *
   * @param \Drupal\cms_content_sync\SyncCore\V1\SyncCore $core
   */
  public function __construct($core) {
    $this->core = $core;
  }

  /**
   * @inheritdoc
   */
  public function getLog($level = NULL) {
    $arguments = [];
    if ($level) {
      $arguments['level'] = (is_array($level) ? implode(',', $level) : $level);
    }

    $items = SimpleQuery
      ::create($this->core, SyncCoreClient::LOG_PATH, $arguments)
        ->execute()
        ->getResult()['items'];

    $items = Helper::obfuscateCredentials($items);

    foreach ($items as &$item) {
      if (isset($item['err']['message'])) {
        $item['msg'] = $item['err']['message'];
      }
    }

    return $items;
  }

  /**
   * @inheritdoc
   */
  public function getStatus() {
    $result = SimpleQuery
      ::create($this->core, SyncCoreClient::STATUS_PATH)
        ->execute()
        ->getResult();

    return [
      'version' => $result['version'],
      'usage' => [
        'today' => [
          // Note that a push from a site means it's an import to the Sync Core
          // so don't get confused by the naming.
          'entitiesPushedFromSites' => $result['runtime_usage']['sumImport'],
          'rootEntitiesPushedFromSites' => $result['runtime_usage']['sumRootImport'],
          'entitiesPulledBySites' => $result['runtime_usage']['sumExport'],
          'rootEntitiesPulledBySites' => $result['runtime_usage']['sumRootExport'],
        ],
      ],
    ];
  }

}
