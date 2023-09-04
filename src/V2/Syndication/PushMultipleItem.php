<?php

namespace EdgeBox\SyncCore\V2\Syndication;

use EdgeBox\SyncCore\Interfaces\Syndication\IPushMultipleItem;
use EdgeBox\SyncCore\V2\Raw\Model\RemoteEntitySummary;
use EdgeBox\SyncCore\V2\Raw\Model\RemoteEntityTranslationDetails;

class PushMultipleItem implements IPushMultipleItem
{
    /**
     * @var RemoteEntitySummary
     */
    protected $dto;

    /**
     * PushMultiple constructor.
     */
    public function __construct(string $type, string $bundle, string $version_id, string $root_language, ?string $uuid, ?string $unique_id)
    {
        $this->dto = new RemoteEntitySummary();

        $this->dto->setEntityTypeNamespaceMachineName($type);
        $this->dto->setEntityTypeMachineName($bundle);
        $this->dto->setEntityTypeVersion($version_id);
        $this->dto->setLanguage($root_language);
        $this->dto->setRemoteUuid($uuid);
        $this->dto->setRemoteUniqueId($unique_id);
        $this->dto->setIsDeleted(false);
        $this->dto->setIsSource(true);
        $this->dto->setPoolMachineNames([]);
        $this->dto->setChangedLanguages([$root_language]);
    }

    /**
     * Add a Pool to the entity. Must provide at least one.
     *
     * @return $this
     */
    public function addPool(string $pool_machine_name)
    {
        $pools = $this->dto->getPoolMachineNames();
        $pools[] = $pool_machine_name;
        $this->dto->setPoolMachineNames($pools);

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function addTranslation(string $language_id, string $view_url, bool $changed = true)
    {
        $translationDto = new RemoteEntityTranslationDetails();
        $translationDto->setLanguage($language_id);
        $translationDto->setViewUrl($view_url);

        $translations = $this->dto->getTranslations();
        if (!$translations) {
            $translations = [];
        }
        $translations[] = $translationDto;

        $this->dto->setTranslations($translations);

        if ($changed) {
            $languages = $this->dto->getChangedLanguages();
            $languages[] = $language_id;
            $this->dto->setChangedLanguages($languages);
        }

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function hasChanged(bool $changed)
    {
        $languages = $this->dto->getChangedLanguages();
        $root_language = $this->dto->getLanguage();
        if ($changed) {
            if (!in_array($root_language, $languages)) {
                $languages[] = $root_language;
            }
        } else {
            $languages = array_diff($languages, [$root_language]);
        }
        $this->dto->setChangedLanguages($languages);

        return $this;
    }

    /**
     * @return $this
     */
    public function setName(string $value)
    {
        $this->dto->setName($value);

        return $this;
    }

    /**
     * @return $this
     */
    public function setSourceDeepLink(string $value)
    {
        $this->dto->setViewUrl($value);

        return $this;
    }

    /**
     * @return $this
     */
    public function isDeleted(bool $is)
    {
        $this->dto->setIsDeleted($is);

        return $this;
    }

    /**
     * @return $this
     */
    public function isSource(bool $is)
    {
        $this->dto->setIsSource($is);

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getData()
    {
        return $this->dto->jsonSerialize();
    }

    public function getDto()
    {
        return $this->dto;
    }
}
