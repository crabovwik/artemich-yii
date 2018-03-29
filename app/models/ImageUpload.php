<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\web\UploadedFile;

class ImageUpload extends Model
{
    /** @var UploadedFile */
    public $file;

    public function __construct(UploadedFile $file, array $config = [])
    {
        parent::__construct($config);

        $this->file = $file;
    }

    public function uploadImage($savePath) {
        $this->file->saveAs($savePath);
    }
}
