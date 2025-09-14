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

    public function download()
    {
        $url = $this->file->getDownloadUrl();

        if (empty($url)) {
            return null;
        }

        return file_get_contents($url);
    }

    public function getId()
    {
        return $this->file->getId();
    }

    public function getFileSize()
    {
        return $this->file->getFileSize();
    }

    public function getMaxFileSize()
    {
        return $this->file->getMaxFileSize();
    }

    public function getFileName()
    {
        return $this->file->getFileName();
    }

    public function getRemoteFilePath()
    {
        return $this->file->getRemoteFilePath();
    }

    public function getHash()
    {
        return $this->file->getHash();
    }

    public function getMimeType()
    {
        return $this->file->getMimeType();
    }
}
