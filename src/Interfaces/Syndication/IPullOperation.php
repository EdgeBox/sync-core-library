<?php

namespace EdgeBox\SyncCore\Interfaces\Syndication;

interface IPullOperation
{
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
    public function getName($language = null);

    /**
     * @return string[]
     */
    public function getUsedTranslationLanguages();

    /**
     * @param array $data
     *                    The data as provided by the source site using
     *                    {@see IPushSingle::embed}, {@see IPushSingle::addReference} or
     *                    {@see IPushSingle::addDependency}
     *
     * @return IEntityReference
     */
    public function loadReference($data);

    /**
     * @param string      $name
     * @param null|string $language
     *
     * @return mixed
     */
    public function getProperty($name, $language = null);

    /**
     * Return the contents of the file that was uploaded on the remote site.
     *
     * @return null|string
     */
    public function downloadFile();

    /**
     * Get the proper response body to return to the Sync Core. Should include a
     * deep link to the entity so that other sites can deep link to this content.
     *
     * @param null|string $entity_deep_link
     *
     * @return array
     */
    public function getResponseBody($entity_deep_link);
}
