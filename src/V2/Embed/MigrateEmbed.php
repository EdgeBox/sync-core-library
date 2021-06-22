<?php

namespace EdgeBox\SyncCore\V2\Embed;

use EdgeBox\SyncCore\Interfaces\Embed\IEmbedService;
use EdgeBox\SyncCore\V2\SyncCore;

class MigrateEmbed extends RegisterSiteEmbed
{
    protected $params;

    public function __construct(SyncCore $core, array $params)
    {
        parent::__construct(
            $core,
            $params,
            IEmbedService::MIGRATE
        );
    }

    protected function getOptions()
    {
        return [
            'site' => parent::getOptions(),
            'pools' => $this->params['pools'],
            'flows' => $this->params['flows'],
            'settings' => $this->params['settings'],
        ];
    }
}
