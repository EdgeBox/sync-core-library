<?php

namespace EdgeBox\SyncCore\V2\Configuration;

use EdgeBox\SyncCore\Interfaces\Configuration\IDefineObject;
use EdgeBox\SyncCore\V2\BatchOperation;
use EdgeBox\SyncCore\V2\Raw\Model\RemoteEntityTypeProperty;
use EdgeBox\SyncCore\V2\Raw\Model\RemoteEntityTypePropertyType;
use EdgeBox\SyncCore\V2\SyncCore;

class DefineProperty extends BatchOperation implements IDefineObject
{
    /**
     * @var SyncCore
     */
    protected $core;

    /**
     * @var RemoteEntityTypeProperty
     */
    protected $dto;

    /**
     * DefineEntityType constructor.
     */
    public function __construct(SyncCore $core, string $machine_name, ?string $name, string $type, bool $required, bool $multiple, ?string $type_name)
    {
        $dto = new RemoteEntityTypeProperty([
            'machineName' => $machine_name,
            'name' => $name ?? $machine_name,
            'type' => $type,
            'required' => $required,
            'multiple' => $multiple,
            'remoteTypeName' => $type_name,
        ]);

        parent::__construct(
            $core,
            BatchOperation::REQUEST_ENTITY_TYPE_CREATE,
            $dto
        );
    }

    /**
     * @return RemoteEntityTypeProperty
     */
    public function getDto()
    {
        return $this->dto;
    }

    /**
     * {@inheritDoc}
     */
    public function addToBatch($batch)
    {
        // Nothing to do as we're part of the entity type definition.
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
        if ($properties) {
            foreach ($properties as $i => $property) {
                if ($property->getMachineName() === $machine_name) {
                    $properties[$i] = $newProperty;
                    $newProperty = null;

                    break;
                }
            }
        } else {
            $properties = [];
        }

        if ($newProperty) {
            $properties[] = $newProperty;
        }

        $this->dto->setProperties($properties);

        return $result;
    }
}
