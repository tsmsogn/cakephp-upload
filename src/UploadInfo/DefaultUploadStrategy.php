<?php

namespace CakephpUpload\UploadInfo;


class DefaultUploadStrategy implements UploadAwareInterface
{

    protected $overwrite;

    protected $filename;

    protected $directory;

    /**
     * UploadAware constructor.
     */
    public function __construct($directory, $filename, $overwrite = false)
    {
        $this->directory = $directory;
        $this->filename = $filename;
        $this->overwrite = $overwrite;
    }

    /**
     * @param $overwrite
     * @return $this
     */
    public function setOverwrite($overwrite)
    {
        $this->overwrite = $overwrite;
        return $this;
    }

    /**
     * @param mixed $filename
     * @return $this
     */
    public function setFilename($filename)
    {
        $this->filename = $filename;
        return $this;
    }

    /**
     * @param mixed $directory
     * @return $this
     */
    public function setDirectory($directory)
    {
        $this->directory = $directory;
        return $this;
    }

    /**
     * @return mixed
     */
    public function isOverwrite()
    {
        return $this->overwrite;
    }

    /**
     * @return mixed
     */
    public function getFilename()
    {
        return $this->filename;
    }

    /**
     * @return mixed
     */
    public function getDirectory()
    {
        return $this->directory;
    }
}
