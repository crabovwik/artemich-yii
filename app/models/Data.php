<?php

namespace app\models;

use Yii;
use yii\helpers\Url;
use yii\web\UploadedFile;

/**
 * This is the model class for table "data".
 *
 * @property int $id
 * @property string $title
 * @property string $description
 * @property string $image
 */
class Data extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'data';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['description'], 'string'],
            [['title', 'image'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => 'Title',
            'description' => 'Description',
            'image' => 'Image',
        ];
    }

    public function saveImage(UploadedFile $file)
    {
        $file->saveAs($this->getImagesFolder() . DIRECTORY_SEPARATOR . $this->getResultImageName($file));
    }

    public function getResultImageName(UploadedFile $file)
    {
        return md5(uniqid('image') . $file->getBaseName()) . ".{$file->getExtension()}";
    }

    public function getImageUrl()
    {
        return Url::to('@web/' . $this->getImagesFolder() . DIRECTORY_SEPARATOR . $this->image);
    }

    public function getImagesFolder()
    {
        return 'uploads';
    }

    public function getImageSavePathData(UploadedFile $file)
    {
        return [
            $this->getImagesFolder(),
            $this->getResultImageName($file),
        ];
    }

    public function beforeDelete()
    {
        if ($this->image) {
            $this->removeImage();
        }

        return parent::beforeDelete();
    }

    public function removeImage()
    {
        $fullPath = $this->getImagesFolder() . DIRECTORY_SEPARATOR . $this->image;

        if (!file_exists($fullPath)) {
            return true;
        }

        return unlink($fullPath);
    }
}
