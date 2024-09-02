<?php

namespace EdgeBox\SyncCore\V2\Configuration;

use EdgeBox\SyncCore\Interfaces\Configuration\IDefineLanguage;
use EdgeBox\SyncCore\V2\BatchOperation;
use EdgeBox\SyncCore\V2\Raw\Model\LanguageDefinition;
use EdgeBox\SyncCore\V2\SyncCore;

class DefineLanguage extends BatchOperation implements IDefineLanguage
{
    /**
     * @var LanguageDefinition
     */
    protected $dto;

    /**
     * DefineFlow constructor.
     */
    public function __construct(SyncCore $core, string $code, string $name)
    {
        parent::__construct(
            $core,
            null,
            new LanguageDefinition()
        );

        $this->dto->setCode($code);
        $this->dto->setName($name);
    }

    /**
     * {@inheritDoc}
     */
    public function isRightToLeft($set = null)
    {
        if (is_bool($set)) {
            $this->dto->setIsRightToLeft($set);
        }

        return (bool) $this->dto->getIsRightToLeft();
    }

    /**
     * {@inheritDoc}
     */
    public function setNativeName($set = null)
    {
        if (null !== $set) {
            $this->dto->setNativeName($set);
        }

        return $this->dto->getNativeName();
    }
}
