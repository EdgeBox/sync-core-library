<?php

namespace EdgeBox\SyncCore\Interfaces\Embed;

use EdgeBox\SyncCore\Helpers\EmbedResult;

interface IEmbedFeature
{
    /**
     * @return EmbedResult
     */
    public function run();
}
