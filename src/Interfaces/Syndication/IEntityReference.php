<?php

namespace EdgeBox\SyncCore\Interfaces\Syndication;

interface IEntityReference
{
    /**
     * Provide the additional details that the remote site passed through
     * {@see IPushSingle::embed}, {@see IPushSingle::addReference} or
     * {@see IPushSingle::addDependency}.
     *
     * @return null|array
     */
    public function getDetails();

    /**
     * Get the entity ID. Can be either a UUID or a shared ID if provided by the
     * remote site. {@see ISyndicationService::pushSingle}.
     *
     * @return string
     */
    public function getId();

    /**
     * Get the entity UUID.
     *
     * @return mixed
     */
    public function getUuid();

    /**
     * @return string
     */
    public function getType();

    /**
     * @return string
     */
    public function getBundle();

    /**
     * @return string
     */
    public function getVersion();

    /**
     * @return bool
     */
    public function isEmbedded();

    /**
     * @return null|IPullOperation
     */
    public function getEmbeddedEntity();

    /**
     * @return null|string
     */
    public function getName();

    /**
     * @return null|string[]
     */
    // TODO: Drupal: Expect multiple
    public function getPoolIds();
}
