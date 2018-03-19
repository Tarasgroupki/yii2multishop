<?php

namespace common\models;

use Yii;
use yii\behaviors\SluggableBehavior;
use yii\behaviors\SluggableBehavior1;
use yii\behaviors\SluggableBehavior2;
use yii\behaviors\SluggableBehavior3;
use yz\shoppingcart\CartPositionInterface;
use yz\shoppingcart\CartPositionTrait;
use pjhl\multilanguage\LangHelper;

/**
 * This is the model class for table "product".
 *
 * @property integer $id
 * @property string $title
 * @property string $slug
 * @property string $description
 * @property integer $category_id
 * @property string $price
 *
 * @property Image[] $images
 * @property OrderItem[] $orderItems
 * @property Category $category
 */
class Product extends \yii\db\ActiveRecord implements CartPositionInterface
{
    use CartPositionTrait;
    public $news_translate = array();
	public $image_url;
	public $file;
	public $file_name;
	public $slug1;
	public $slug2;
	public $slug3;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'product';
    }

    public function behaviors()
    {
        return [
           'slug' => [
                'class' => SluggableBehavior::className(),
                'attribute' => 'news_translate',
				//'transliterateOptions' => 'Russian-Latin/BGN; Any-Latin; Latin-ASCII; NFD; [:Nonspacing Mark:] Remove; NFC;'
            ],
			'slug1' => [
			  'class' => SluggableBehavior1::className(),
              'attribute' => 'news_translate',
			],
			'slug2' => [
			  'class' => SluggableBehavior2::className(),
              'attribute' => 'news_translate',
			],
			'slug3' => [
			  'class' => SluggableBehavior3::className(),
              'attribute' => 'news_translate',
			]
       ];
    }


    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
		    [['news_translate'],'validatorRequiredWords'],
            [['description'], 'string'],
            [['category_id'], 'integer'],
            [['price'], 'number'],
            [['title'], 'string', 'max' => 255],
			[['category_id','price'],'required'],
			[['file'],'file','skipOnEmpty' => true,'extensions' => 'png, jpg, jpeg']
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
            'slug' => 'Slug',
            'description' => 'Description',
            'category_id' => 'Category ID',
            'price' => 'Price',
        ];
    }

	public function validatorRequiredWords()
{ 
    foreach ( $this->news_translate as $news ) {
        if(empty($news['title']) && empty($news['description'])) {
			$this->addError('news_translate', 'Не заповнені всі поля!');     
        }
    }
}
	public function getProduct()
	{
		return $this->hasMany(Product::className(), ['lang_id' => 'id']);
	}
    /**
     * @return Image[]
     */
    public function getImages()
    {
        return $this->hasMany(Image::className(), ['prod_id' => 'product_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOrderItems()
    {
        return $this->hasMany(OrderItem::className(), ['product_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCategory()
    {
        return $this->hasOne(Category::className(), ['id' => 'category_id']);
    }

    /**
     * @inheritdoc
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * @inheritdoc
     */
    public function getId()
    {
        return $this->product_id;
    }
	public function insertNews($max,$price,$cat_id,$slug)
	{ 
			 //$file_id = $this->Max_news();
			 $connection = Yii::$app->db; 	
			/* $component->attachBehavior('SluggableBehavior', [
    'class' => SluggableBehavior::className(),
    'attribute' => 'title',
]);*/
//$max = $connection->createCommand('SELECT MAX(product_id) FROM `product`')->queryOne();
			 //print_r($max);
			 foreach($this->news_translate as $key => $name){
				 //echo $name['title'];
				 //$component->attribute = $name['title'];
				 $name['price'] = $price;
				 //$name['lang_id'] = $mod
				 //$name['slug'] = $model->slug;
				 //print_r($max);
				 $name['product_id'] = $max['MAX(product_id)']+1;
				 $name['category_id'] = $cat_id;
				 if($key == 1){
					 $name['slug'] = $slug;
				 }
				 if($key == 2){
					 $name['slug'] = $this->slug1;
				 }
				 if($key == 3){
					 $name['slug'] = $this->slug2;
				 }
				// echo $name['slug'];
				// echo $this->slug1;
				// echo $this->slug2;
				// $name['id'] = Yii::$app->user->identity->id;
				 $names[] = $name; 
				//echo $name;
			 }
			// print_r($names);
			$connection->createCommand()->batchInsert(Product::tableName(),['id','lang_id','title','description','price','product_id','category_id','slug']
			,$names)->execute();
	}
	/*public function UpdateNews($id)
	{
		$connection = Yii::$app->db;
		foreach($this->news_update as $key => $name){
				 $names[] = $name; 
			 }
		$query = $connection->queryBuilder->batchInsert('news',['news_id','lang_id','news_name','news_description']
		,$names);
		$connection->createCommand($query . " ON DUPLICATE KEY UPDATE `lang_id` = VALUES(`lang_id`), `news_name`= VALUES(`news_name`),`news_description` = VALUES(`news_description`)")->execute();
	}*/
	public function UpdateNews($slug)
	{//echo $this->slug;
		$connection = Yii::$app->db;
		//print_r($this->news_translate);
		foreach($this->news_translate as $key => $name){
				 //echo $key;
				 $name['category_id'] = $this->category_id;
				 $name['price'] = $this->price;
				  if($key == 1){
					  //echo $slug;
					 $name['slug'] = $this->slug3;
				 }
				 if($key == 2){
					 $name['slug'] = $this->slug1;
				 }
				 if($key == 3){
					 $name['slug'] = $this->slug2;
				 }
				 $names[] = $name;
			 }
		$query = $connection->queryBuilder->batchInsert('product',['id','lang_id','title','description','category_id','price','slug']
		,$names);
		$connection->createCommand($query . " ON DUPLICATE KEY UPDATE `lang_id` = VALUES(`lang_id`), `title` = VALUES(`title`), `description`= VALUES(`description`),`category_id` = VALUES(`category_id`),`price` = VALUES(`price`),`slug` = VALUES(`slug`)")->execute();
	}
	public static function deletePost($id)
	{
		$connection = Yii::$app->db;
		$connection->createCommand()
		->delete('product','product_id = '.$id.'')
		->execute();
	}
	public function Max_news()
	{
		$connection = Yii::$app->db;
		$file_id = $connection->createCommand('SELECT MAX(product_id) FROM `product` GROUP BY `lang_id`')->queryAll();	
	    return $file_id;
	}
	 public function uploadImage($filename)
	{
		$connection = Yii::$app->db;
			if ($this->file) {  						
				$file_id = $this->Max_news();
				/*$connection->createCommand()->batchInsert('g_photo',['gallery_id','name'],[
		    [$file_id[0]['MAX(product_id)'] + 1,$filename],
		])->execute();*/
		$connection->createCommand()->batchInsert('image',['prod_id','image_url'],[
		    [$file_id[0]['MAX(product_id)'],$filename],
		])->execute();
		}
	}
	public function getViewProducts($id)
	{
		return static::find() ->select('*,product.product_id,product.id')           
		    ->LeftJoin('image', 'product.product_id = image.prod_id')->where(['product.product_id'=>$id])->andwhere(['product.lang_id'=>LangHelper::getLanguage('id')])->one();;
	}
}
