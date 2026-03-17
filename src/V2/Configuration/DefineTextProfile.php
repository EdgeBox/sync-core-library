<?php

namespace EdgeBox\SyncCore\V2\Configuration;

use EdgeBox\SyncCore\Interfaces\Configuration\IDefineTextProfile;
use EdgeBox\SyncCore\Interfaces\Configuration\IDefineTextProfileHtmlPolicy;
use EdgeBox\SyncCore\Interfaces\Configuration\IDefineTextProfileMarkdownPolicy;
use EdgeBox\SyncCore\V2\BatchOperation;
use EdgeBox\SyncCore\V2\Raw\Model\CreateTextProfileDto;
use EdgeBox\SyncCore\V2\Raw\Model\HtmlPolicy;
use EdgeBox\SyncCore\V2\Raw\Model\MarkdownPolicy;
use EdgeBox\SyncCore\V2\Raw\Model\TextContentType;
use EdgeBox\SyncCore\V2\SyncCore;

class DefineTextProfile extends BatchOperation implements IDefineTextProfile
{
    /**
     * @var CreateTextProfileDto
     */
    protected $dto;

    /**
     * @var null|DefineTextProfileHtmlPolicy
     */
    protected $html_policy;

    /**
     * @var null|DefineTextProfileMarkdownPolicy
     */
    protected $markdown_policy;

    public function __construct(SyncCore $core, string $machine_name, $content_type, ?string $label = null)
    {
        parent::__construct(
            $core,
            null,
            new CreateTextProfileDto()
        );

        $this->dto->setMachineName($machine_name);

        /** @var TextContentType $content_type_value */
        $content_type_value = $content_type;
        $this->dto->setContentType($content_type_value);

        if (null !== $label) {
            $this->dto->setLabel($label);
        }
    }

    public function setLabel($set = null)
    {
        if (null !== $set) {
            $this->dto->setLabel($set);
        }

        return $this->dto->getLabel();
    }

    public function setContentType($set = null)
    {
        if (null !== $set) {
            /** @var TextContentType $set_value */
            $set_value = $set;
            $this->dto->setContentType($set_value);
        }

        return $this->dto->getContentType();
    }

    public function html(): IDefineTextProfileHtmlPolicy
    {
        if (null === $this->html_policy) {
            $dto = $this->dto->getHtmlPolicy();

            if (null === $dto) {
                $dto = new HtmlPolicy();
                $this->dto->setHtmlPolicy($dto);
            }

            $this->html_policy = new DefineTextProfileHtmlPolicy($dto);
        }

        return $this->html_policy;
    }

    public function markdown(): IDefineTextProfileMarkdownPolicy
    {
        if (null === $this->markdown_policy) {
            $dto = $this->dto->getMarkdownPolicy();

            if (null === $dto) {
                $dto = new MarkdownPolicy();
                $this->dto->setMarkdownPolicy($dto);
            }

            $this->markdown_policy = new DefineTextProfileMarkdownPolicy($dto);
        }

        return $this->markdown_policy;
    }
}
