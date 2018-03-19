<?php

namespace common\models;

use Yii;
use yii\web\UploadedFile;

/**
 * This is the model class for table "image".
 *
 * @property integer $id
 * @property integer $product_id
 *
 * @property Product $product
 */
class Image extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'image';
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'product_id' => 'Product ID',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProduct()
    {
        return $this->hasOne(Product::className(), ['id' => 'product_id']);
    }

    /**
     * @return string image hash
     */
    protected function getHash()
    {
		//echo md5($this->product_id . '-' . $this->id);
        return md5($this->product_id . '-' . $this->id);
    }

    /**
     * @return string path to image file
     */
    public function getPath()
    {
		//echo Yii::getAlias('@frontend/web/images/' . $this->getHash() . '.jpg');
        return Yii::getAlias('@frontend/web/images/' . $this->getHash() . '.jpg');
    }

    /**
     * @return string URL of the image
     */
    public function getUrl()
    {
		//echo Yii::getAlias('http://www.shop1.ua/images/' . $this->getHash() . '.jpg');
        //return Yii::getAlias('http://www.shop1.ua/frontend/web/images/' . $this->getHash() . '.jpg');
        return $this->image_url;
	}
    public function afterDelete()
    {
        unlink($this->getPath());
        parent::afterDelete();
    }
}
