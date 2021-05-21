<?php

namespace EdgeBox\SyncCore\V1\Configuration;

use EdgeBox\SyncCore\Interfaces\Configuration\IDefineEntityType;
use EdgeBox\SyncCore\V1\BatchOperation;
use EdgeBox\SyncCore\V1\Entity\Entity;
use EdgeBox\SyncCore\V1\Storage\ApiStorage;
use EdgeBox\SyncCore\V1\Storage\ConnectionStorage;
use EdgeBox\SyncCore\V1\Storage\CustomStorage;
use EdgeBox\SyncCore\V1\Storage\EntityTypeStorage;
use EdgeBox\SyncCore\V1\Storage\InstanceStorage;

class DefineEntityType extends BatchOperation implements IDefineEntityType
{
    /**
     * @var \EdgeBox\SyncCore\V1\SyncCore
     */
    protected $core;

    /**
     * @var string
     */
    protected $pool_id;

    /**
     * @var string
     */
    protected $type_machine_name;

    /**
     * @var string
     */
    protected $bundle_machine_name;

    /**
     * @var string
     */
    protected $version_id;

    /**
     * @var array
     */
    protected $body;

    public function __construct($core, $pool_id, $type_machine_name, $bundle_machine_name, $version_id)
    {
        $entity_type_id = EntityTypeStorage::getExternalEntityTypeId($pool_id, $type_machine_name, $bundle_machine_name, $version_id);

        parent::__construct(
      $core,
      EntityTypeStorage::ID,
      [
          'id' => $entity_type_id,
          'name_space' => $type_machine_name,
          'name' => $bundle_machine_name,
          'version' => $version_id,
          'base_class' => 'api-unify/services/drupal/v0.1/models/base.model',
          'custom' => true,
          'new_properties' => [
              'source' => [
                  'type' => 'reference',
                  'default_value' => null,
                  'connection_identifiers' => [
                      [
                          'properties' => [
                              'id' => 'source_connection_id',
                          ],
                      ],
                  ],
                  'model_identifiers' => [
                      [
                          'properties' => [
                              'id' => 'source_id',
                          ],
                      ],
                  ],
                  'multiple' => false,
              ],
              'source_id' => [
                  'type' => 'id',
                  'default_value' => null,
              ],
              'source_connection_id' => [
                  'type' => 'id',
                  'default_value' => null,
              ],
              Entity::PROPERTY_PREVIEW_HTML => [
                  'type' => 'string',
                  'default_value' => null,
              ],
              Entity::PROPERTY_SOURCE_DEEP_LINK_URL => [
                  'type' => 'string',
                  'default_value' => null,
              ],
              'apiu_translation' => [
                  'type' => 'object',
                  'default_value' => null,
              ],
              'metadata' => [
                  'type' => 'object',
                  'default_value' => null,
              ],
              'embed_entities' => [
                  'type' => 'object',
                  'default_value' => null,
                  'multiple' => true,
              ],
              'menu_items' => [
                  'type' => 'object',
                  'default_value' => null,
                  'multiple' => true,
              ],
              Entity::PROPERTY_NAME => [
                  'type' => 'string',
                  'default_value' => null,
              ],
              'created' => [
                  'type' => 'int',
                  'default_value' => null,
              ],
              'changed' => [
                  'type' => 'int',
                  'default_value' => null,
              ],
              'uuid' => [
                  'type' => 'string',
                  'default_value' => null,
              ],
          ],
          'new_property_lists' => [
              'list' => [
                  '_resource_url' => 'value',
                  '_resource_connection_id' => 'value',
                  'id' => 'value',
              ],
              'reference' => [
                  '_resource_url' => 'value',
                  '_resource_connection_id' => 'value',
                  'id' => 'value',
              ],
              'details' => [
                  '_resource_url' => 'value',
                  '_resource_connection_id' => 'value',
                  'id' => 'value',
                  'source' => 'reference',
                  'apiu_translation' => 'value',
                  'metadata' => 'value',
                  'embed_entities' => 'value',
                  'title' => 'value',
                  'created' => 'value',
                  'changed' => 'value',
                  'uuid' => 'value',
                  'url' => 'value',
                  'menu_items' => 'value',
              ],
              'database' => [
                  'id' => 'value',
                  'source_id' => 'value',
                  'source_connection_id' => 'value',
                  'preview' => 'value',
                  'url' => 'value',
                  'apiu_translation' => 'value',
                  'metadata' => 'value',
                  'embed_entities' => 'value',
                  'title' => 'value',
                  'created' => 'value',
                  'changed' => 'value',
                  'uuid' => 'value',
                  'menu_items' => 'value',
              ],
              'modifiable' => [
                  'preview' => 'value',
                  'url' => 'value',
                  'apiu_translation' => 'value',
                  'metadata' => 'value',
                  'embed_entities' => 'value',
                  'title' => 'value',
                  'created' => 'value',
                  'changed' => 'value',
                  'menu_items' => 'value',
              ],
              'required' => [
                  'uuid' => 'value',
              ],
          ],
          'api_id' => $pool_id.'-'.ApiStorage::CUSTOM_API_VERSION,
      ]
    );

        $this->type_machine_name = $type_machine_name;
        $this->bundle_machine_name = $bundle_machine_name;
        $this->pool_id = $pool_id;
        $this->version_id = $version_id;

        $pool_connection_id = CustomStorage::getCustomId($pool_id, InstanceStorage::POOL_SITE_ID, $type_machine_name, $bundle_machine_name);

        $this->addDownstream(
      ConnectionStorage::ID,
      [
          'id' => $pool_connection_id,
          'name' => 'Drupal pool connection for '.$type_machine_name.'-'.$bundle_machine_name.'-'.$version_id,
          'hash' => CustomStorage::getCustomPath($pool_id, InstanceStorage::POOL_SITE_ID, $type_machine_name, $bundle_machine_name),
          'usage' => 'EXTERNAL',
          'status' => 'READY',
          'options' => [
              // $cms_content_sync_disable_optimization,
              'update_all' => false,
          ],
          'entity_type_id' => $entity_type_id,
      ]
    );
    }

    /**
     * @return string
     */
    public function getTypeMachineName()
    {
        return $this->type_machine_name;
    }

    /**
     * @return string
     */
    public function getBundleMachineName()
    {
        return $this->bundle_machine_name;
    }

    /**
     * @return string
     */
    public function getVersionId()
    {
        return $this->version_id;
    }

    /**
     * {@inheritdoc}
     */
    public function isTranslatable($set)
    {
        if ($set) {
            $this->addObjectProperty('apiu_translation');
        }

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function addObjectProperty($name, $multiple = false, $required = false)
    {
        $this->addProperty($name, 'object', $multiple, $required);

        return $this;
    }

    /**
     * @param string $name
     * @param string $type
     * @param bool   $multiple
     * @param bool   $required
     */
    protected function addProperty($name, $type, $multiple, $required)
    {
        if (isset($this->body['new_properties'][$name])) {
            return;
        }

        $this->body['new_properties'][$name] = [
            'type' => $type,
            'default_value' => null,
            'multiple' => $multiple,
        ];

        $this->body['new_property_lists']['details'][$name] = 'value';
        $this->body['new_property_lists']['database'][$name] = 'value';

        if ($required) {
            $this->body['new_property_lists']['required'][$name] = 'value';
        }

        $this->body['new_property_lists']['modifiable'][$name] = 'value';
    }

    /**
     * {@inheritdoc}
     */
    public function isFile($set)
    {
        if ($set) {
            $this->body['new_properties']['apiu_file_content'] = [
                'type' => 'string',
                'default_value' => null,
            ];
            $this->body['new_property_lists']['details']['apiu_file_content'] = 'value';
            $this->body['new_property_lists']['filesystem']['apiu_file_content'] = 'value';
            $this->body['new_property_lists']['modifiable']['apiu_file_content'] = 'value';
            $this->body['new_property_lists']['required']['apiu_file_content'] = 'value';
        }

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function addBooleanProperty($name, $multiple = false, $required = false)
    {
        $this->addProperty($name, 'boolean', $multiple, $required);

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function addIntegerProperty($name, $multiple = false, $required = false)
    {
        $this->addProperty($name, 'integer', $multiple, $required);

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function addFloatProperty($name, $multiple = false, $required = false)
    {
        $this->addProperty($name, 'float', $multiple, $required);

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function addStringProperty($name, $multiple = false, $required = false)
    {
        $this->addProperty($name, 'string', $multiple, $required);

        return $this;
    }
}
