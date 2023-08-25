<?php

namespace EdgeBox\SyncCore\Interfaces\Syndication;

interface IFile
{
    /**
     * Get the file contents. Only works if the file was already uploaded.
     *
     * @return null|string
     */
    public function download();

    /**
     * Get the ID of the file in the Sync Core.
     *
     * @return string
     */
    public function getId();

    /**
     * Get the file size in bytes.
     *
     * @return null|number
     */
    public function getFileSize();

    /**
     * Get the file max allowed file size for the upload, if any.
     *
     * @return null|number
     */
    public function getMaxFileSize();

    /**
     * Get the name of the file.
     *
     * @return string
     */
    public function getFileName();

    /**
     * Get the remote file path. Drupal for example uses "private://..." or
     * "public://...".
     *
     * @return null|string
     */
    public function getRemoteFilePath();

    /**
     * Get the hash of the file to optimize upload/download.
     *
     * @return string
     */
    public function getHash();

    /**
     * Get the MIME type, if any was given.
     *
     * @return null|string
     */
    public function getMimeType();
}
