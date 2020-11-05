<?php

namespace EdgeBox\SyncCore\Interfaces\Syndication;

/**
 *
 */
interface IPullOperation {

  /**
   * @return string
   */
  public function getId();

  /**
   * @return string
   */
  public function getUuid();

  /**
   * @return string
   */
  public function getSourceUrl();

  /**
   * @param null|string $language
   *
   * @return string
   */
  public function getName($language = NULL);

  /**
   * @return string[]
   */
  public function getUsedTranslationLanguages();

  /**
   * @param array $data
   *   The data as provided by the source site using
   *   {@see IPushSingle::embed}, {@see IPushSingle::addReference} or
   *    {@see IPushSingle::addDependency}.
   *
   * @return IEntityReference
   */
  public function loadReference($data);

  /**
   * @param string $name
   * @param string|null $language
   *
   * @return mixed
   */
  public function getProperty($name, $language = NULL);

  /**
   * Return the contents of the file that was uploaded on the remote site.
   *
   * @return string|null
   */
  public function downloadFile();

  /**
   * Get the proper response body to return to the Sync Core. Should include a
   * deep link to the entity so that other sites can deep link to this content.
   *
   * @param string|null $entity_deep_link
   *
   * @return array
   */
  public function getResponseBody($entity_deep_link);

}
