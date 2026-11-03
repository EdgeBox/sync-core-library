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
     * @return null|string
     */
    public function getVersionId(?string $language = null, ?bool $including_translations = false);

    /**
     * @return string
     */
    public function getSourceUrl(?string $language = null);

    /**
     * @return string
     */
    public function getName(?string $language = null);

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
    public function loadReference(array $data);

    /**
     * @param array $properties
     *                    The properties that must match
     *
     * @return IEntityReference[]
     */
    public function loadReferencesByProperties(array $properties);

    /**
     * When embedding entities, some of them will already be pulled when the main entity is pulled
     * because the entity references them and then they're imported so they can be de-referenced.
     * But some entities like menu items in Drupal are just embedded into the content without being
     * directly referenced by it. These entities must be pulled after the main entity.
     * So put a while loop around this to and when it's NULL, cancel.
     *
     * @return null|IPullOperation
     */
    public function getNextUnprocessedEmbed();

    /**
     * @return mixed
     */
    public function getProperty(string $name, ?string $language = null);

    /**
     * Return a file object that was uploaded on the remote site to get meta
     * information like file name and size or to download the file.
     *
     * @return null|IFile
     */
    public function loadFile();

    /**
     * Return the contents of the file that was uploaded on the remote site.
     * Use `$this->loadFile()->download()` instead.
     *
     * @deprecated
     *
     * @return null|string
     */
    public function downloadFile();

    /**
     * Get the proper response body to return to the Sync Core. Should include a
     * deep link to the entity so that other sites can deep link to this content.
     *
     * With a map from {@see IPullOperation::setSiteLanguages}, the language of
     * the root and of each translation is the one the site stored it in.
     *
     * @return array
     */
    public function getResponseBody(?string $entity_deep_link, ?string $target_remote_uuid = null);

    /**
     * Set the languages the site stored the pulled content in, so that
     * {@see IPullOperation::getResponseBody} reports them instead of the
     * source's for every response body built after this call.
     *
     * The language of the response body's root and of each of its
     * translations is replaced through the map when the map has it as a key.
     * A translation is left out of the body when the site stored it in
     * another entry: the map gives it the language of the root or of an
     * earlier translation the map moved, or the map moved the root or another
     * translation to its language. Entries the map does not move are kept, so
     * a map without any of the body's languages, or an empty map, leaves the
     * body as it is. A site that stored content in other languages passes the
     * map for every answer about that content.
     *
     * @param array<string, string> $site_languages
     *                                              The source's language codes as keys, the site's
     *                                              language codes they were stored in as values
     */
    public function setSiteLanguages(array $site_languages): void;
}
