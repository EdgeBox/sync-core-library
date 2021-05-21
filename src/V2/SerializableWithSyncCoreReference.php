<?php

namespace EdgeBox\SyncCore\V2;

abstract class SerializableWithSyncCoreReference implements \Serializable
{
    /**
     * @var SyncCore
     */
    protected $core;

    /**
     * SerializableWithSyncCoreReference constructor.
     */
    public function __construct(SyncCore $core)
    {
        $this->core = $core;
    }

    /**
     * @return string[]
     */
    protected function serializeSyncCore()
    {
        return [$this->core->getBaseUrl(), get_class($this->core->getApplication())];
    }

    /**
     * @param string[] $serialized
     */
    public function unserializeSyncCore(array $serialized)
    {
        $base_url = $serialized[0];
        $app = $serialized[1];

        $this->core = SyncCore::get($base_url, $app::get());
    }
}
