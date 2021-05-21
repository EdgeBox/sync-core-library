<?php

namespace EdgeBox\SyncCore\V2\Embed;

// TODO: Library: Move to library.
class EmbedResult {
  const TYPE_REDIRECT = 'redirect';
  const TYPE_RENDER = 'render';

  /**
   * @var string $type
   */
  protected $type;

  /**
   * @var string $details
   */
  protected $details;

  /**
   * EmbedResult constructor.
   *
   * @param string $type
   * @param string $details
   */
  public function __construct(string $type, string $details) {
    $this->type = $type;
    $this->details = $details;
  }

  /**
   * @return string
   */
  public function getRedirectUrl() {
    return $this->type===self::TYPE_REDIRECT
        ? $this->details
        : NULL;
  }

  /**
   * @return string
   */
  public function getRenderedHtml() {
    return $this->type===self::TYPE_RENDER
        ? $this->details
        : NULL;
  }
}
