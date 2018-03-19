<?php

namespace onmotion\gallery\models;

use Yii;

/**
 * This is the model class for table "g_photo".
 *
 * @property string $photo_id
 * @property string $gallery_id
 * @property string $name
 */
class GalleryPhoto extends \yii\db\ActiveRecord
{
    public $files;
	public $gallery_name;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'g_photo';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['gallery_id'], 'integer'],
            [['gallery_name'], 'string', 'max' => 250],
            [['files'], 'file', 'maxFiles' => 100, 'extensions' => 'png, jpg'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'photo_id' => 'ID',
            'gallery_id' => 'Gallery_ID',
            'name' => 'Name',
        ];
    }

    public function getGallery()
    {
        return $this->hasOne(Gallery::className(), ['gallery_id' => 'gallery_id']);
    }
	public function insertPhoto($img,$max)
	{
		$connection = Yii::$app->db;
        $gallery_id = $connection->createCommand('SELECT `gallery_id` FROM `g_gallery` WHERE `newsapp_id` ='.$max.'')->queryOne();
		$photos = array(0=>array('photo_id'=>'','gallery_id'=>$gallery_id['gallery_id'] + 1,'name'=>$img));
		$connection->createCommand()->batchInsert('g_photo',['photo_id','gallery_id','name']
		,$photos)->execute();
	}
	
public function makeImgThumb($filepath,$thumbpath,$extension)
	{
		list($width, $height) = getimagesize($filepath);
// загрузка
$thumb = imagecreatetruecolor(110, 110);
switch ($extension){
case 'png':
$source = imagecreatefrompng($filepath);
break;
case 'jpeg':
$source = imagecreatefromjpeg($filepath);
break;
case 'gif':
$source = imagecreatefromgif($filepath);
break;
}
// изменение размера
imagecopyresized($thumb, $source, 0, 0, 0, 0, 110, 110, $width, $height);
// вывод
switch ($extension){
      case 'png':
      imagepng($thumb,$thumbpath);
      break;
      case 'jpeg':
      imagejpeg($thumb,$thumbpath);
      break;
      case 'gif':
      imagegif($thumb,$thumbpath);
      break;
      default:
      throw new UserException('unknown image type');
      break;
}	
	}
	 public function delTree($dir) 
    { 
	if ($objs = glob($dir."/*")) 
       {
       foreach($objs as $obj)
          {
            is_dir($obj) ? $this->delTree($obj) : unlink($obj);
          }
       }
    rmdir($dir);
    }
	public function getAllPhotos($id)
	{
		return static::find()->select('*')->LeftJoin('g_gallery','g_photo.gallery_id = g_gallery.gallery_id')->where(['g_gallery.newsapp_id' => $id])->all();
	}
}
