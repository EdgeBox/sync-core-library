<?php

namespace EdgeBox\SyncCore\V2\Configuration;

use EdgeBox\SyncCore\Interfaces\Configuration\IDefineEntityType;
use EdgeBox\SyncCore\V2\BatchOperation;
use EdgeBox\SyncCore\V2\Raw\Model\CreateRemoteEntityTypeVersionDto;
use EdgeBox\SyncCore\V2\Raw\Model\RemoteEntityTypeProperty;
use EdgeBox\SyncCore\V2\Raw\Model\RemoteEntityTypePropertyType;
use EdgeBox\SyncCore\V2\SyncCore;

class DefineEntityType extends BatchOperation implements IDefineEntityType
{
    /**
     * @var SyncCore
     */
    protected $core;

    /**
     * @var CreateRemoteEntityTypeVersionDto
     */
    protected $dto;

    public const FILE_PROPERTY_NAME = '__file__';

    /**
     * DefineEntityType constructor.
     *
     * @param string|null $name
     */
    public function __construct(SyncCore $core, string $namespace_machine_name, string $machine_name, string $version_id, $name = null)
    {
        $dto = new CreateRemoteEntityTypeVersionDto();

        $dto->setAppType($core->getApplication()->getApplicationId());
        $dto->setName($name ?? $machine_name);
        $dto->setNamespaceMachineName($namespace_machine_name);
        $dto->setMachineName($machine_name);
        $dto->setVersionId($version_id);
        $dto->setTranslatable(false);
        $dto->setProperties([]);

        parent::__construct(
      $core,
      BatchOperation::REQUEST_ENTITY_TYPE_CREATE,
      $dto
    );
    }

    /**
     * @return string
     */
    public function getNamespaceMachineName()
    {
        return $this->dto->getNamespaceMachineName();
    }

    /**
     * @return string
     */
    public function getMachineName()
    {
        return $this->getMachineName();
    }

    /**
     * @return string
     */
    public function getVersionId()
    {
        return $this->getVersionId();
    }

    /**
     * {@inheritdoc}
     */
    public function isTranslatable($set)
    {
        if ($set) {
            $this->dto->setTranslatable(true);
        }

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function isFile($set)
    {
        if ($set) {
            $this->addProperty(
          self::FILE_PROPERTY_NAME,
          RemoteEntityTypePropertyType::FILE,
          false,
          true
      );
        }

        return $this;
    }

    /**
     * @param string $name
     */
    protected function addProperty(string $machine_name, string $type, bool $multiple, bool $required, $name = null)
    {
        $newProperty = new RemoteEntityTypeProperty([
      'machineName' => $machine_name,
      'name' => $name ?? $machine_name,
      'type' => $type,
      'required' => $required,
      'multiple' => $multiple,
    ]);

        /**
         * @var RemoteEntityTypeProperty[] $properties
         */
        $properties = $this->dto->getProperties();
        foreach ($properties as $i => $property) {
            if ($property->getMachineName() === $machine_name) {
                $properties[$i] = $newProperty;
                $newProperty = null;
                break;
            }
        }

        if ($newProperty) {
            $properties[] = $newProperty;
        }

        $this->dto->setProperties($properties);
    }

    /**
     * {@inheritdoc}
     */
    public function addBooleanProperty($name, $multiple = false, $required = false)
    {
        $this->addProperty($name, RemoteEntityTypePropertyType::BOOLEAN, $multiple, $required);

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function addIntegerProperty($name, $multiple = false, $required = false)
    {
        $this->addProperty($name, RemoteEntityTypePropertyType::INTEGER, $multiple, $required);

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function addFloatProperty($name, $multiple = false, $required = false)
    {
        $this->addProperty($name, RemoteEntityTypePropertyType::FLOAT, $multiple, $required);

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function addStringProperty($name, $multiple = false, $required = false)
    {
        $this->addProperty($name, RemoteEntityTypePropertyType::STRING, $multiple, $required);

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function addObjectProperty($name, $multiple = false, $required = false)
    {
        $this->addProperty($name, RemoteEntityTypePropertyType::OBJECT, $multiple, $required);

        return $this;
    }
}
