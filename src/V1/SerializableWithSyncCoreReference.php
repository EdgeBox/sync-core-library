<?php

namespace EdgeBox\SyncCore\V1;

abstract class SerializableWithSyncCoreReference implements \Serializable
{
    /**
     * @var SyncCore
     */
    protected $core;

    /**
     * SerializableWithSyncCoreReference constructor.
     *
     * @param SyncCore $core
     */
    public function __construct($core)
    {
        $this->core = $core;
    }

    /**
     * @param string[] $serialized
     */
    public function unserializeSyncCore($serialized)
    {
        $base_url = $serialized[0];
        $app = $serialized[1];

        $this->core = SyncCore::get($base_url, $app::get());
    }

    /**
     * @return string[]
     */
    protected function serializeSyncCore()
    {
        return [
            $this->core->getBaseUrl(),
            get_class($this->core->getApplication()),
        ];
    }
}
