<?php

namespace EdgeBox\SyncCore\Interfaces\Configuration;

use EdgeBox\SyncCore\Interfaces\IBatchOperation;
use EdgeBox\SyncCore\V2\Raw\Model\TextContentType;

interface IDefineTextProfile extends IBatchOperation
{
    /**
     * Define or retrieve the label.
     *
     * @param null|string $set
     *
     * @return null|string
     */
    public function setLabel($set = null);

    /**
     * Define or retrieve the content type.
     *
     * @param null|TextContentType $set
     *
     * @return TextContentType
     */
    public function setContentType($set = null);

    /**
     * Configure and retrieve the HTML policy helper.
     *
     * @return IDefineTextProfileHtmlPolicy
     */
    public function html();

    /**
     * Configure and retrieve the Markdown policy helper.
     *
     * @return IDefineTextProfileMarkdownPolicy
     */
    public function markdown();
}
