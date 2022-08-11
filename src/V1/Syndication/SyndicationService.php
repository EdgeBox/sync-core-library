<?php

namespace EdgeBox\SyncCore\V1\Syndication;

use EdgeBox\SyncCore\Exception\SyncCoreException;
use EdgeBox\SyncCore\Interfaces\Syndication\ISyndicationService;
use EdgeBox\SyncCore\V1\Query\Condition\DataCondition;
use EdgeBox\SyncCore\V1\Query\Condition\ParentCondition;
use EdgeBox\SyncCore\V1\Query\SimpleQuery;
use EdgeBox\SyncCore\V1\Storage\CustomStorage;
use EdgeBox\SyncCore\V1\Storage\InstanceStorage;
use EdgeBox\SyncCore\V1\Storage\MetaInformationConnectionStorage;
use EdgeBox\SyncCore\V1\SyncCoreClient;

class SyndicationService implements ISyndicationService
{
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
    public function massPull()
    {
        throw new \Exception("The Sync Core v1 doesn't support mass updates.");
    }

    /**
     * {@inheritdoc}
     */
    public function massPush()
    {
        throw new \Exception("The Sync Core v1 doesn't support mass updates.");
    }

    /**
     * {@inheritdoc}
     */
    public function configurePullDashboard()
    {
        return new ConfigurePullDashboard($this->core);
    }

    /**
     * {@inheritdoc}
     */
    public function pullSingle(string $flow_id, string $type, string $bundle, string $entity_id)
    {
        return new TriggerPullSingle($this->core, $type, $bundle, $entity_id);
    }

    /**
     * {@inheritdoc}
     */
    public function pullAll(string $flow_id, string $type, string $bundle, string $version)
    {
        return new PullAll($this->core, $type, $bundle);
    }

    /**
     * {@inheritdoc}
     */
    public function handlePull(string $flow_id, string $type, string $bundle, array $data, bool $delete)
    {
        return new PullOperation($this->core, $type, $bundle, $data);
    }

    /**
     * {@inheritdoc}
     */
    public function pushSingle(string $flow_id, string $type, string $bundle, string $version_id, string $root_language, string $entity_uuid, ?string $entity_id)
    {
        return new PushSingle($this->core, $type, $bundle, $entity_uuid, $entity_id);
    }

    /**
     * {@inheritDoc}
     */
    public function pushMultiple(string $flow_id)
    {
        throw new \Exception("The Sync Core v1 doesn't support mass updates.");
    }

    /**
     * {@inheritDoc}
     */
    public function deletedLocally(string $flow_id, string $type, string $bundle, string $root_language, string $entity_uuid, ?string $entity_id)
    {
    }

    /**
     * {@inheritdoc}
     */
    public function getExternalUsages(string $pool_id, string $type, string $bundle, string $shared_entity_id)
    {
        /**
         * @var \EdgeBox\SyncCore\V1\Storage\MetaInformationConnectionStorage $storage
         */
        $storage = $this
            ->core
            ->storage->getMetaInformationConnectionStorage();

        $application = $this->core->getApplication();

        $items = $storage->listItems()
            ->orderBy('connection_id')
            ->setCondition(
                ParentCondition::all()
                    ->add(DataCondition::equal(MetaInformationConnectionStorage::PROPERTY_ENTITY_ID, $shared_entity_id))
                    ->add(DataCondition::startsWith(MetaInformationConnectionStorage::PROPERTY_CONNECTION_ID, $application->getApplicationId().'-'.$pool_id.'-'))
                    ->add(DataCondition::endsWith(MetaInformationConnectionStorage::PROPERTY_CONNECTION_ID, '-'.$type.'-'.$bundle))
            )
            ->getDetails()
            ->execute()
            ->getAll()
        ;

        $result = [];

        foreach ($items as $item) {
            if (!empty($item['deleted_at'])) {
                continue;
            }
            $connection_id = !empty($item['connection']['id']) ? $item['connection']['id'] : $item['connection_id'];
            $site_id = preg_replace('@^'.$application->getApplicationId().'-'.$pool_id.'-(.+)-'.$type.'-'.$bundle.'$@', '$1', $connection_id);

            if (InstanceStorage::POOL_SITE_ID == $site_id) {
                continue;
            }

            if ($site_id == $application->getSiteMachineName()) {
                continue;
            }

            if (!empty($item['entity']['_resource_url'])) {
                $entity = SimpleQuery
          ::create($this->core, SyncCoreClient::getRelativeUrl($item['entity']['_resource_url']))
              ->execute()
              ->getResult()
                ;
            } else {
                $storage = new CustomStorage(
                    $this->core,
                    $pool_id,
                    $site_id,
                    $type,
                    $bundle
                );

                try {
                    $entity = $storage
                        ->getItem($shared_entity_id)
                        ->execute()
                        ->getItem()
                    ;
                } catch (SyncCoreException $e) {
                    continue;
                }
            }

            $result[$site_id] = $entity['url'];
        }

        return $result;
    }

    /**
     * {@inheritdoc}
     */
    public function refreshAuthentication()
    {
        $connections = $this->getConnectionEntities();

        $result = true;

        foreach ($connections as $connection) {
            $result &= $connection
                ->login()
                ->execute()
                ->succeeded()
            ;
        }

        return $result;
    }

    /**
     * Get a list of all Sync Core connections as resource URLs.
     *
     * @throws \Exception
     *
     * @return \EdgeBox\SyncCore\V1\Entity\Connection[]
     */
    protected function getConnectionEntities()
    {
        $storage = $this->core->storage->getConnectionStorage();
        $result = [];

        $items = $storage
            ->listItems()
            ->setCondition(DataCondition::equal('instance_id', $this->core->getApplication()
            ->getSiteId()))
            ->execute()
            ->getAll()
        ;

        foreach ($items as $item) {
            $result[] = $storage->getEntity($item['id']);
        }

        return $result;
    }
}
