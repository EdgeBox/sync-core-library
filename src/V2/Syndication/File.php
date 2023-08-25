<?php

namespace EdgeBox\SyncCore\V2\Syndication;

use EdgeBox\SyncCore\Interfaces\Syndication\IFile;
use EdgeBox\SyncCore\V2\Raw\Model\FileEntity;

class File implements IFile
{
    protected $file;

    public function __construct(FileEntity $file)
    {
        $this->file = $file;
    }

    /**
     * {@inheritDoc}
     */
    public function download()
    {
        $url = $this->file->getDownloadUrl();

        if (empty($url)) {
            return null;
        }

        return file_get_contents($url);
    }

    /**
     * {@inheritDoc}
     */
    public function getId()
    {
        return $this->file->getId();
    }

    /**
     * {@inheritDoc}
     */
    public function getFileSize()
    {
        return $this->file->getFileSize();
    }

    /**
     * {@inheritDoc}
     */
    public function getMaxFileSize()
    {
        return $this->file->getMaxFileSize();
    }

    /**
     * {@inheritDoc}
     */
    public function getFileName()
    {
        return $this->file->getFileName();
    }

    /**
     * {@inheritDoc}
     */
    public function getRemoteFilePath()
    {
        return $this->file->getRemoteFilePath();
    }

    /**
     * {@inheritDoc}
     */
    public function getHash()
    {
        return $this->file->getHash();
    }

    /**
     * {@inheritDoc}
     */
    public function getMimeType()
    {
        return $this->file->getMimeType();
    }
}
