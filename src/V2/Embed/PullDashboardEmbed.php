<?php

namespace EdgeBox\SyncCore\V2\Embed;

use EdgeBox\SyncCore\Interfaces\IApplicationInterface;
use EdgeBox\SyncCore\V2\SyncCore;

// TODO: Library: add interface.
// TODO: Library: Refactor ConfigurePullDashboard to be an Embed class in V1.
// TODO: Drupal: Use embed instead of the ConfigurePullDashboard.
class PullDashboardEmbed extends Embed
{
    /**
     * @var array
     */
    protected $options;

    public function __construct(SyncCore $core, $params)
    {
        $this->options = $params;

        parent::__construct(
        $core,
        Embed::PULL_DASHBOARD,
            IApplicationInterface::SYNC_CORE_PERMISSIONS_CONTENT
    );
    }

    protected function getOptions()
    {
        return $this->options;
    }

    public function run()
    {
        return $this->render();
    }

    /**
     * {@inheritdoc}
     *
     * Returning NULL means we don't want to use the dashboard from the Drupal module but
     * rather provide our own.
     */
    public function getConfig()
    {
        return null;
    }

    public function ifTaggedWith($pool_id, $type, $bundle, $property, $uuids)
    {
    }

    public function forEntityType($pool_id, $type, $bundle)
    {
    }

    public function searchInTitle($text)
    {
    }

    public function searchInPreview($text)
    {
    }

    public function publishedBetween($from, $to)
    {
    }
}
