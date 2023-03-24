<?php

namespace EdgeBox\SyncCore\V2\Configuration;

use EdgeBox\SyncCore\Interfaces\Configuration\IDefineEntityType;
use EdgeBox\SyncCore\V2\Batch;
use EdgeBox\SyncCore\V2\BatchOperation;
use EdgeBox\SyncCore\V2\Raw\Model\CreateRemoteEntityTypeVersionDto;
use EdgeBox\SyncCore\V2\Raw\Model\RemoteEntityTypeProperty;
use EdgeBox\SyncCore\V2\Raw\Model\RemoteEntityTypePropertyType;
use EdgeBox\SyncCore\V2\SyncCore;

class DefineEntityType extends BatchOperation implements IDefineEntityType
{
    public const FILE_PROPERTY_NAME = '__file__';

    /**
     * @var SyncCore
     */
    protected $core;

    /**
     * @var CreateRemoteEntityTypeVersionDto
     */
    protected $dto;

    /**
     * DefineEntityType constructor.
     *
     * @param null|string $name
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
     * {@inheritDoc}
     */
    public function addToBatch($batch)
    {
        /**
         * @var Batch $batch
         */
        $batch->prependOperation($this);

        return $this;
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
        return $this->dto->getMachineName();
    }

    /**
     * @return string
     */
    public function getVersionId()
    {
        return $this->dto->getVersionId();
    }

    /**
     * {@inheritdoc}
     */
    public function isTranslatable(bool $set)
    {
        if ($set) {
            $this->dto->setTranslatable(true);
        }

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function isFile(bool $set)
    {
        if ($set) {
            $this->addProperty(
                self::FILE_PROPERTY_NAME,
                'File content',
                RemoteEntityTypePropertyType::FILE,
                false,
                true,
                '__file__'
            );
        }

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function addBooleanProperty(string $machine_name, ?string $name, $multiple = false, $required = false, ?string $type_name = null)
    {
        $this->addProperty($machine_name, $name, RemoteEntityTypePropertyType::BOOLEAN, $multiple, $required, $type_name);
    }

    /**
     * {@inheritdoc}
     */
    public function addIntegerProperty(string $machine_name, ?string $name, $multiple = false, $required = false, ?string $type_name = null)
    {
        $this->addProperty($machine_name, $name, RemoteEntityTypePropertyType::INTEGER, $multiple, $required, $type_name);
    }

    /**
     * {@inheritdoc}
     */
    public function addFloatProperty(string $machine_name, ?string $name, $multiple = false, $required = false, ?string $type_name = null)
    {
        $this->addProperty($machine_name, $name, RemoteEntityTypePropertyType::FLOAT, $multiple, $required, $type_name);
    }

    /**
     * {@inheritdoc}
     */
    public function addStringProperty(string $machine_name, ?string $name, $multiple = false, $required = false, ?string $type_name = null)
    {
        $this->addProperty($machine_name, $name, RemoteEntityTypePropertyType::STRING, $multiple, $required, $type_name);
    }

    /**
     * {@inheritdoc}
     */
    public function addObjectProperty(string $machine_name, ?string $name, $multiple = false, $required = false, ?string $type_name = null)
    {
        return $this->addProperty($machine_name, $name, RemoteEntityTypePropertyType::OBJECT, $multiple, $required, $type_name);
    }

    /**
     * {@inheritdoc}
     */
    public function addReferenceProperty(string $machine_name, ?string $name, $multiple = false, $required = false, ?string $type_name = null)
    {
        $this->addProperty($machine_name, $name, RemoteEntityTypePropertyType::REFERENCE, $multiple, $required, $type_name);
    }

    /**
     * Add a property of the given type.
     *
     * @return DefineProperty
     */
    protected function addProperty(string $machine_name, ?string $name, string $type, bool $multiple, bool $required, ?string $type_name)
    {
        $result = new DefineProperty(
            $this->core,
            $machine_name,
            $name,
            $type,
            $required,
            $multiple,
            $type_name,
        );

        $newProperty = $result->getDto();

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

        return $result;
    }
}
