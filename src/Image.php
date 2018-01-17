<?php

namespace zeroonebeatz\image;

use yii\web\UploadedFile;

class Image
{
  private $model;
  private $file;

  public function __construct(\yii\db\ActiveRecord $model = null)
  {
    $this->model = $model;
    $this->file = new File($model->tableName());
  }

  public function upload($attr, $fileAttr)
  {
    if (isset($_FILES)){
      $file = UploadedFile::getInstance($this->model, $fileAttr);
      $newFile = $this->file->upload($file);
      $this->model->$attr = $newFile ?: $this->model->$attr;
    }
  }

  public function delete($attr)
  {
    $this->file->delete($this->model->$attr);
    $this->model->$attr = '';

    return $this->model->save();
  }

  public static function original($path) //TODO связать с моделью
  {
    $image = new self();
    return !empty($path) ? $image->file->uploadPath($path) : '';
  }

  public static function thumb($filename, $width = null, $height = null, $crop = true) //TODO разбить на части, передать создание файлов и директорий
  {
    $image = new self();
    return $image->file->thumb($filename, $width, $height, $crop);
  }
}
