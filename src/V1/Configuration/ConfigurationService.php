<?php

namespace EdgeBox\SyncCore\V1\Configuration;

use EdgeBox\SyncCore\Interfaces\Configuration\IConfigurationService;
use EdgeBox\SyncCore\Interfaces\Configuration\IRemoteFlow;
use EdgeBox\SyncCore\V1\Query\Condition\DataCondition;
use EdgeBox\SyncCore\V1\Query\Condition\ParentCondition;
use EdgeBox\SyncCore\V1\Storage\ApiStorage;
use EdgeBox\SyncCore\V1\Storage\ObjectStorage;
use EdgeBox\SyncCore\V1\Storage\PreviewEntityStorage;

class ConfigurationService implements IConfigurationService
{
    /**
     * The type to identify what kind of object we want to query in the general
     * object storage.
     */
    public const OBJECT_STORAGE_TYPE = 'content_sync-drupal-flow-config';

    public const OBJECT_STORAGE_PROPERTY_MODULE_VERSION = ObjectStorage::PROPERTY_PROPERTIES.'.module_version';

    public const OBJECT_STORAGE_PROPERTY_POOLS = ObjectStorage::PROPERTY_PROPERTIES.'.pools';

    /**
     * @var \EdgeBox\SyncCore\V1\SyncCore
     */
    protected $core;

    /**
     * SyndicationService constructor.
     *
     * @param \EdgeBox\SyncCore\V1\SyncCore $core
     */
    public function __construct($core)
    {
        $this->core = $core;
    }

    /**
     * {@inheritdoc}
     */
    public function listRemoteFlows(string $remote_module_version)
    {
        return new ListRemoteFlows($this->core, $remote_module_version);
    }

    /**
     * {@inheritdoc}
     */
    public function getRemoteFlow(string $id)
    {
        $object_storage = new ObjectStorage($this->core);

        $item = $object_storage
      ->getItem($id)
      ->execute()
      ->getItem();

        return new class($item) implements IRemoteFlow {
            protected $item;

            public function __construct($item)
            {
                $this->item = $item;
            }

            public function getName()
            {
                return $this->item['properties']['name'];
            }

            public function getSiteName()
            {
                return $this->item['properties']['site'];
            }

            public function getConfig()
            {
                return $this->item['properties']['config'];
            }
        };
    }

    /**
     * {@inheritdoc}
     */
    public function defineFlow(string $machine_name, string $name, ?string $config)
    {
        return new DefineFlow($this->core, $machine_name, $name, $config);
    }

    /**
     * {@inheritdoc}
     */
    public function defineEntityType(string $pool_id, string $type_machine_name, string $bundle_machine_name, string $version_id, ?string $name = null)
    {
        return new DefineEntityType($this->core, $pool_id, $type_machine_name, $bundle_machine_name, $version_id);
    }

    /**
     * {@inheritdoc}
     */
    public function listRemotePools()
    {
        $api_storage = new ApiStorage($this->core);
        $remote_pools = $api_storage
      ->listItems()
      ->setCondition(
        ParentCondition::all()
          ->add(DataCondition::equal(ApiStorage::PROPERTY_VERSION, ApiStorage::CUSTOM_API_VERSION))
          ->add(DataCondition::equal(ApiStorage::PROPERTY_PARENT_ID, $this->core->getApplication()
              ->getApplicationId().'-'.ApiStorage::CUSTOM_API_VERSION))
      )
      ->execute()
      ->getAll();

        $options = [];
        foreach ($remote_pools as $option) {
            $id = preg_replace('@-'.ApiStorage::CUSTOM_API_VERSION.'$@', '', $option['id']);

            $options[$id] = $option['name'];
        }

        return $options;
    }

    /**
     * {@inheritdoc}
     */
    public function usePool(string $pool_id, string $pool_name)
    {
        $this->registerDrupalApi();

        return new RegisterPool($this->core, $pool_id, $pool_name);
    }

    /**
     * Create API entity for Drupal that the other types inherit from.
     *
     * @throws \EdgeBox\SyncCore\Exception\SyncCoreException
     */
    protected function registerDrupalApi()
    {
        // Only do it once (performance).
        static $done = false;
        if ($done) {
            return;
        }
        $done = true;

        $body = [
            'id' => 'drupal-'.ApiStorage::CUSTOM_API_VERSION,
            'name' => 'drupal',
            'version' => ApiStorage::CUSTOM_API_VERSION,
        ];

        $this
      ->core
      ->storage->getApiStorage()
      ->createItem($body)
      ->execute();
    }

    /**
     * {@inheritdoc}
     */
    public function enableEntityPreviews($public_access_possible = false)
    {
        // Only do it once (performance).
        static $done = false;
        if ($done) {
            return $this;
        }
        $done = true;

        if ($public_access_possible) {
            $public_access = $this->core->isDirectUserAccessEnabled();
            if (null === $public_access) {
                $public_access = true;
            }
        } else {
            $public_access = false;
        }

        $body = [
            'id' => PreviewEntityStorage::ID,
            'name' => 'Drupal preview connection',
            'hash' => PreviewEntityStorage::EXTERNAL_PREVIEW_PATH,
            'usage' => 'EXTERNAL',
            'status' => 'READY',
            'entity_type_id' => PreviewEntityStorage::PREVIEW_ENTITY_ID,
            'options' => [
                'crud' => [
                    'read_list' => [],
                ],
                'static_values' => [],
                PreviewEntityStorage::PUBLIC_ACCESS_OPTION_NAME => $public_access,
            ],
        ];

        $this
      ->core
      ->storage->getConnectionStorage()
      ->createItem($body)
      ->execute();

        return $this;
    }

    /**
     * @return $this
     */
    public function deleteFlows(array $keep_machine_names)
    {
        return $this;
    }
}
