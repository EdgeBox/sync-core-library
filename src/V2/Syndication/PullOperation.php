<?php

namespace EdgeBox\SyncCore\V2\Syndication;

use EdgeBox\SyncCore\Interfaces\Syndication\IEntityReference;
use EdgeBox\SyncCore\Interfaces\Syndication\IPullOperation;
use EdgeBox\SyncCore\V2\Configuration\DefineEntityType;
use EdgeBox\SyncCore\V2\Raw\Model\CreateRemoteEntityRevisionDto;
use EdgeBox\SyncCore\V2\Raw\Model\FileEntity;
use EdgeBox\SyncCore\V2\Raw\Model\RemoteEntityDependency;
use EdgeBox\SyncCore\V2\Raw\Model\RemoteEntityEmbed;
use EdgeBox\SyncCore\V2\Raw\ObjectSerializer;
use EdgeBox\SyncCore\V2\SyncCore;

/**
 *
 */
class PullOperation implements IPullOperation {

  /**
   * @var SyncCore $core
   */
  protected $core;

  /**
   * @var string $type
   */
  protected $type;

  /**
   * @var string $bundle
   */
  protected $bundle;

  /**
   * @var CreateRemoteEntityRevisionDto $dto
   */
  protected $dto;

  /**
   * @var CreateRemoteEntityRevisionDto[] $translations
   */
  protected $translations;

  /**
   * PushSingle constructor.
   *
   * @param SyncCore $core
   * @param string $type
   * @param string $bundle
   * @param array $body
   */
  public function __construct(SyncCore $core, string $type, string $bundle, array $body) {
    $this->core = $core;
    $this->type = $type;
    $this->bundle = $bundle;
    $this->dto = new CreateRemoteEntityRevisionDto($body);
    $this->translations = [];

    $translations = $this->dto->getTranslations();
    if($translations) {
      foreach($translations as $translation_dto) {
        $language = $translation_dto->getLanguage();
        $this->translations[$language] = $translation_dto;
      }
    }
  }

  /**
   * @inheritdoc
   */
  public function getId() {
    return $this->dto->getRemoteUniqueId() ?? $this->dto->getRemoteUuid();
  }

  /**
   * @inheritdoc
   */
  public function getUuid() {
    return $this->dto->getRemoteUuid();
  }

  /**
   * @inheritdoc
   */
  public function getSourceUrl() {
    return $this->dto->getViewUrl();
  }

  /**
   * @inheritdoc
   */
  public function getUsedTranslationLanguages() {
    return array_keys($this->translations);
  }

  /**
   * @inheritdoc
   */
  public function getProperty($name, $language = NULL) {
    $properties = $language ? $this->translations[$language]->getProperties() : $this->dto->getProperties();
    foreach($properties as $property) {
      if($property->getName()===$name) {
        return $property->getValue();
      }
    }

    return NULL;
  }

  /**
   * @inheritdoc
   */
  public function loadReference($definition) {
    /**
     * @var RemoteEntityDependency $referenceDto
     */
    $referenceDto = ObjectSerializer::deserialize($definition, RemoteEntityDependency::class);

    $embeds = $this->dto->getEmbed();
    $embed = NULL;
    for($i=0; $i<count($embeds); $i++) {
      $candidate = $embeds[$i];
      // TODO: Interface/Drupal: We need to save the language per reference.
      if($candidate->getEntityTypeNamespaceMachineName()===$referenceDto->getEntityTypeNamespaceMachineName() &&
          $candidate->getEntityTypeMachineName()===$referenceDto->getEntityTypeMachineName() &&
          $candidate->getRemoteUuid()===$referenceDto->getRemoteUuid() &&
          $candidate->getRemoteUniqueId()===$referenceDto->getRemoteUniqueId()) {
        $embed = $candidate;
      }
    }

    return new class ($this->core, $referenceDto, $embed) implements IEntityReference {
      /**
       * @var SyncCore
       */
      protected $core;
      /**
       * @var RemoteEntityDependency
       */
      protected $dto;
      /**
       * @var RemoteEntityEmbed|null
       */
      protected $embed;

      /**
       * constructor.
       *
       * @param SyncCore $core
       * @param RemoteEntityDependency $dto
       * @param RemoteEntityEmbed|null $embed
       */
      public function __construct(SyncCore $core, RemoteEntityDependency $dto, ?RemoteEntityEmbed $embed=NULL) {
        $this->core = $core;
        $this->dto = $dto;
        $this->embed = $embed;
      }

      /**
       * @inheritdoc
       */
      public function getDetails() {
        /**
         * @var array|null $details
         */
        $details = $this->dto->getReferenceDetails();
        return $details;
      }

      /**
       * @inheritdoc
       */
      public function getId() {
        return $this->dto->getRemoteUniqueId();
      }

      /**
       * @inheritdoc
       */
      public function getUuid() {
        return $this->dto->getRemoteUuid();
      }

      /**
       * @inheritdoc
       */
      public function getType() {
        return $this->dto->getEntityTypeNamespaceMachineName();
      }

      /**
       * @inheritdoc
       */
      public function getBundle() {
        return $this->dto->getEntityTypeMachineName();
      }

      /**
       * @inheritdoc
       */
      public function getVersion() {
        return $this->dto->getEntityTypeVersion();
      }

      /**
       * @inheritdoc
       */
      public function getName() {
        return $this->dto->getName();
      }

      /**
       * @inheritdoc
       */
      public function getPoolId() {
        // TODO: Support multiple.
        return $this->dto->getPoolMachineNames()[0];
      }

      /**
       * @inheritdoc
       */
      public function isEmbedded() {
        return !!$this->embed;
      }

      /**
       * @inheritdoc
       */
      public function getEmbeddedEntity() {
        return new PullOperation(
            $this->core,
            $this->getType(),
            $this->getBundle(),
            $this->embed->jsonSerialize());
      }
    };
  }

  /**
   * @inheritdoc
   */
  public function downloadFile() {
    $reference = $this->getProperty(DefineEntityType::FILE_PROPERTY_NAME);
    if(empty($reference) || empty($reference['id'])) {
      return NULL;
    }

    $request = $this->core->getClient()->fileControllerItemRequest($reference['id']);
    /**
     * @var FileEntity $file
     */
    $file = $this->core->sendToSyncCoreAndExpect($request, FileEntity::class, SyncCore::SYNC_CORE_PERMISSIONS_CONTENT);

    if(empty($file->getDownloadUrl())) {
      return NULL;
    }

    return file_get_contents($file->getDownloadUrl());
  }

  /**
   * @inheritdoc
   */
  public function getName($language = NULL) {
    $dto = $language ? $this->translations[$language] : $this->dto;
    return $dto->getName();
  }

  /**
   * @inheritdoc
   */
  public function getResponseBody($entity_deep_link) {
    $data = $this->dto->jsonSerialize();
    $data['viewUrl'] = $entity_deep_link;
    return $data;
  }
}
