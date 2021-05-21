<?php

namespace EdgeBox\SyncCore\V2\Embed;

// TODO: Library: Move to library.
class EmbedResult
{
    public const TYPE_REDIRECT = 'redirect';
    public const TYPE_RENDER = 'render';

    /**
     * @var string
     */
    protected $type;

    /**
     * @var string
     */
    protected $details;

    /**
     * EmbedResult constructor.
     */
    public function __construct(string $type, string $details)
    {
        $this->type = $type;
        $this->details = $details;
    }

    /**
     * @return string
     */
    public function getRedirectUrl()
    {
        return self::TYPE_REDIRECT === $this->type
        ? $this->details
        : null;
    }

    /**
     * @return string
     */
    public function getRenderedHtml()
    {
        return self::TYPE_RENDER === $this->type
        ? $this->details
        : null;
    }
}
