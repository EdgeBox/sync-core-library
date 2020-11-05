<?php

namespace EdgeBox\SyncCore\Interfaces\Configuration;

use EdgeBox\SyncCore\Interfaces\IBatchOperation;

/**
 *
 */
interface IDefineEntityType extends IBatchOperation {

  /**
   * @param bool $set
   *
   * @return $this
   */
  public function isTranslatable($set);

  /**
   * @param bool $set
   *
   * @return $this
   */
  public function isFile($set);

  /**
   * @param string $name
   * @param bool $multiple
   * @param bool $required
   *
   * @return $this
   */
  public function addBooleanProperty($name, $multiple = FALSE, $required = FALSE);

  /**
   * @param string $name
   * @param bool $multiple
   * @param bool $required
   *
   * @return $this
   */
  public function addIntegerProperty($name, $multiple = FALSE, $required = FALSE);

  /**
   * @param string $name
   * @param bool $multiple
   * @param bool $required
   *
   * @return $this
   */
  public function addFloatProperty($name, $multiple = FALSE, $required = FALSE);

  /**
   * @param string $name
   * @param bool $multiple
   * @param bool $required
   *
   * @return $this
   */
  public function addStringProperty($name, $multiple = FALSE, $required = FALSE);

  /**
   * @param string $name
   * @param bool $multiple
   * @param bool $required
   *
   * @return $this
   */
  public function addObjectProperty($name, $multiple = FALSE, $required = FALSE);

}
