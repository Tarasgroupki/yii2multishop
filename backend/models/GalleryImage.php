<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "gallery_image".
 *
 * @property integer $id
 * @property integer $newsapp_id
 * @property string $type
 * @property string $ownerId
 * @property integer $rank
 * @property string $name
 * @property string $description
 */
class GalleryImage extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'gallery_image';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['newsapp_id', 'rank'], 'integer'],
            [['ownerId'], 'required'],
            [['description'], 'string'],
            [['type', 'ownerId', 'name'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'newsapp_id' => 'Newsapp ID',
            'type' => 'Type',
            'ownerId' => 'Owner ID',
            'rank' => 'Rank',
            'name' => 'Name',
            'description' => 'Description',
        ];
    }
	public function behaviors()
{
    return [
         'galleryBehavior' => [
             'class' => GalleryBehavior::className(),
             'type' => 'product',
             'extension' => 'jpg',
             'directory' => Yii::getAlias('@webroot') . '/images/product/gallery',
             'url' => Yii::getAlias('@web') . '/images/product/gallery',
             'versions' => [
                 'small' => function ($img) {
                     /** @var \Imagine\Image\ImageInterface $img */
                     return $img
                         ->copy()
                         ->thumbnail(new \Imagine\Image\Box(200, 200));
                 },
                 'medium' => function ($img) {
                     /** @var Imagine\Image\ImageInterface $img */
                     $dstSize = $img->getSize();
                     $maxWidth = 800;
                     if ($dstSize->getWidth() > $maxWidth) {
                         $dstSize = $dstSize->widen($maxWidth);
                     }
                     return $img
                         ->copy()
                         ->resize($dstSize);
                 },
             ]
         ]
    ];
}
}
