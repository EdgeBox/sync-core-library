<?php

namespace SyncCore\Interfaces;

/**
 *
 */
interface IProgress {

  /**
   * @return int
   */
  public function total();

  /**
   * @return int
   */
  public function progress();

}
