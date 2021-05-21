<?php

namespace EdgeBox\SyncCore\V2\Configuration;

use EdgeBox\SyncCore\Interfaces\Configuration\IDefineEntityType;
use EdgeBox\SyncCore\V2\BatchOperation;
use EdgeBox\SyncCore\V2\Raw\Model\CreateRemoteEntityTypeVersionDto;
use EdgeBox\SyncCore\V2\Raw\Model\RemoteEntityTypeProperty;
use EdgeBox\SyncCore\V2\Raw\Model\RemoteEntityTypePropertyType;
use EdgeBox\SyncCore\V2\SyncCore;

/**
 *
 */
class DefineEntityType extends BatchOperation implements IDefineEntityType {

  /**
   * @var SyncCore
   */
  protected $core;

  /**
   * @var CreateRemoteEntityTypeVersionDto
   */
  protected $dto;

  const FILE_PROPERTY_NAME = '__file__';

  /**
   * DefineEntityType constructor.
   * @param SyncCore $core
   * @param string $namespace_machine_name
   * @param string $machine_name
   * @param string $version_id
   * @param null|string $name
   */
  public function __construct(SyncCore $core, string $namespace_machine_name, string $machine_name, string $version_id, $name=NULL) {
    $dto = new CreateRemoteEntityTypeVersionDto();

    $dto->setAppType($core->getApplication()->getApplicationId());
    $dto->setName($name ?? $machine_name);
    $dto->setNamespaceMachineName($namespace_machine_name);
    $dto->setMachineName($machine_name);
    $dto->setVersionId($version_id);
    $dto->setTranslatable(FALSE);
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
  public function getNamespaceMachineName() {
    return $this->dto->getNamespaceMachineName();
  }

  /**
   * @return string
   */
  public function getMachineName() {
    return $this->getMachineName();
  }

  /**
   * @return string
   */
  public function getVersionId() {
    return $this->getVersionId();
  }

  /**
   * @inheritdoc
   */
  public function isTranslatable($set) {
    if ($set) {
      $this->dto->setTranslatable(TRUE);
    }
    return $this;
  }

  /**
   * @inheritdoc
   */
  public function isFile($set) {
    if ($set) {
      $this->addProperty(
          self::FILE_PROPERTY_NAME,
          RemoteEntityTypePropertyType::FILE,
          FALSE,
          TRUE
      );
    }
    return $this;
  }

  /**
   * @param string $machine_name
   * @param string $type
   * @param bool $multiple
   * @param bool $required
   * @param string $name
   */
  protected function addProperty(string $machine_name, string $type, bool $multiple, bool $required, $name=NULL) {
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
    foreach($properties as $i=>$property) {
      if($property->getMachineName()===$machine_name) {
        $properties[$i] = $newProperty;
        $newProperty = NULL;
        break;
      }
    }

    if($newProperty) {
      $properties[] = $newProperty;
    }

    $this->dto->setProperties($properties);
  }

  /**
   * @inheritdoc
   */
  public function addBooleanProperty($name, $multiple = FALSE, $required = FALSE) {
    $this->addProperty($name, RemoteEntityTypePropertyType::BOOLEAN, $multiple, $required);
    return $this;
  }

  /**
   * @inheritdoc
   */
  public function addIntegerProperty($name, $multiple = FALSE, $required = FALSE) {
    $this->addProperty($name, RemoteEntityTypePropertyType::INTEGER, $multiple, $required);
    return $this;
  }

  /**
   * @inheritdoc
   */
  public function addFloatProperty($name, $multiple = FALSE, $required = FALSE) {
    $this->addProperty($name, RemoteEntityTypePropertyType::FLOAT, $multiple, $required);
    return $this;
  }

  /**
   * @inheritdoc
   */
  public function addStringProperty($name, $multiple = FALSE, $required = FALSE) {
    $this->addProperty($name, RemoteEntityTypePropertyType::STRING, $multiple, $required);
    return $this;
  }

  /**
   * @inheritdoc
   */
  public function addObjectProperty($name, $multiple = FALSE, $required = FALSE) {
    $this->addProperty($name, RemoteEntityTypePropertyType::OBJECT, $multiple, $required);
    return $this;
  }

}
