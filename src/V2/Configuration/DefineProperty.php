<?php

namespace EdgeBox\SyncCore\V2\Configuration;

use EdgeBox\SyncCore\Interfaces\Configuration\IDefineBooleanProperty;
use EdgeBox\SyncCore\Interfaces\Configuration\IDefineFloatProperty;
use EdgeBox\SyncCore\Interfaces\Configuration\IDefineIntegerProperty;
use EdgeBox\SyncCore\Interfaces\Configuration\IDefineObjectProperty;
use EdgeBox\SyncCore\Interfaces\Configuration\IDefineReferenceProperty;
use EdgeBox\SyncCore\Interfaces\Configuration\IDefineStringProperty;
use EdgeBox\SyncCore\V2\BatchOperation;
use EdgeBox\SyncCore\V2\Raw\Model\RegularExpression;
use EdgeBox\SyncCore\V2\Raw\Model\RemoteEntityPropertyDraft;
use EdgeBox\SyncCore\V2\Raw\Model\RemoteEntityTypeProperty;
use EdgeBox\SyncCore\V2\Raw\Model\RemoteEntityTypePropertyType;
use EdgeBox\SyncCore\V2\Raw\Model\RemoteEntityTypeRestriction;
use EdgeBox\SyncCore\V2\SyncCore;

class DefineProperty extends BatchOperation implements IDefineObjectProperty, IDefineBooleanProperty, IDefineFloatProperty, IDefineIntegerProperty, IDefineReferenceProperty, IDefineStringProperty
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
        return $this->addProperty($machine_name, $name, RemoteEntityTypePropertyType::BOOLEAN, $multiple, $required, $type_name);
    }

    /**
     * {@inheritdoc}
     */
    public function addIntegerProperty(string $machine_name, ?string $name, $multiple = false, $required = false, ?string $type_name = null)
    {
        return $this->addProperty($machine_name, $name, RemoteEntityTypePropertyType::INTEGER, $multiple, $required, $type_name);
    }

    /**
     * {@inheritdoc}
     */
    public function addFloatProperty(string $machine_name, ?string $name, $multiple = false, $required = false, ?string $type_name = null)
    {
        return $this->addProperty($machine_name, $name, RemoteEntityTypePropertyType::FLOAT, $multiple, $required, $type_name);
    }

    /**
     * {@inheritdoc}
     */
    public function addStringProperty(string $machine_name, ?string $name, $multiple = false, $required = false, ?string $type_name = null)
    {
        return $this->addProperty($machine_name, $name, RemoteEntityTypePropertyType::STRING, $multiple, $required, $type_name);
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
        return $this->addProperty($machine_name, $name, RemoteEntityTypePropertyType::REFERENCE, $multiple, $required, $type_name);
    }

    /**
     * {@inheritdoc}
     */
    public function setMainProperty(string $machine_name)
    {
        $this->dto->setMainProperty($machine_name);

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function setNameProperty(string $machine_name)
    {
        $this->dto->setNameProperty($machine_name);

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function addAllowedValue(string $name, mixed $value = null)
    {
        $allowed_values = $this->dto->getAllowedValues() ?? [];
        $allowed_values[] = new RemoteEntityPropertyDraft([
            'name' => $name,
            'value' => null === $value ? $name : $value,
        ]);
        $this->dto->setAllowedValues($allowed_values);

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function setFormat(string $format)
    {
        $this->dto->setFormat($format);

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function setMinItems(int $min)
    {
        $this->dto->setMinItems($min);

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function setMaxItems(int $min)
    {
        $this->dto->setMaxItems($min);

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function setLocalized(bool $set)
    {
        $this->dto->setLocalized($set);

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function setMinValue(mixed $minValue)
    {
        $this->dto->setMinValue($minValue);

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function setMaxValue(mixed $maxValue)
    {
        $this->dto->setMaxValue($maxValue);

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function setUnit(string $unit)
    {
        $this->dto->setUnit($unit);

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function setByteSize(int $byteCount)
    {
        $this->dto->setByteSize($byteCount);

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function setMinLength(int $minLength)
    {
        $this->dto->setMinLength($minLength);

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function setMaxLength(int $maxLength)
    {
        $this->dto->setMaxLength($maxLength);

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function setEncoding(string $encoding)
    {
        $this->dto->setEncoding($encoding);

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function setRegularExpressionFormat(string $php_pattern)
    {
        $delimiter = $php_pattern[0];
        $end = strrpos($php_pattern, $delimiter);

        $pattern = substr($php_pattern, 1, $end - 1);
        $flags = substr($pattern, $end + 1);

        $expression = new RegularExpression([
            'pattern' => $pattern,
            'caseless' => false !== strpos($flags, 'i'),
            'multiline' => false !== strpos($flags, 'm'),
            'dotAll' => false !== strpos($flags, 's'),
        ]);

        $this->dto->setRegularExpressionFormat($expression);

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function addAllowedType(string $namespaceMachineName, ?string $machineName = null)
    {
        $allowed = $this->dto->getAllowedEntityTypes() ?? [];
        $restriction = new RemoteEntityTypeRestriction([
            'namespaceMachineName' => $namespaceMachineName,
            'machineName' => $machineName,
        ]);
        $allowed[] = $restriction;
        $this->dto->setAllowedEntityTypes($allowed);

        return $this;
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
