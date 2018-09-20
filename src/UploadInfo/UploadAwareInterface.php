<?php

namespace CakephpUpload\UploadInfo;


interface UploadAwareInterface
{

    public function isOverwrite();

    public function getFilename();

    public function getDirectory();
}
