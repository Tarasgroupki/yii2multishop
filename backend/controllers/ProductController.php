<?php

namespace backend\controllers;

use common\models\Category;
use Yii;
use common\models\Product;
use backend\models\ProductSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * ProductController implements the CRUD actions for Product model.
 */
class ProductController extends Controller
{
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                ],
            ],
        ];
    }

    /**
     * Lists all Product models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new ProductSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
       // echo '<pre>'.print_r($dataProvider,true).'</pre>';      
	   return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Product model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
		//echo Product::getProduct();
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Product model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
		$langs = array(1 => array('lang_id'=>1,'name'=>'English'),2 => array('lang_id'=>2,'name'=>'Russian'),3 => array('lang_id'=>3,'name'=>'Ukrainian'));
        $categories = Category::find()->all();
        $model = new Product();
		$connection = Yii::$app->db;
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
			//echo '<pre>'.print_r($model,true).'</pre>';
			$max = $connection->createCommand('SELECT MAX(product_id) FROM `product`')->queryOne();
			//echo $model->slug;
			$model->insertNews($max,$model->price,$model->category_id,$model->slug);
            //print_r($max);
			//echo $max['MAX(product_id)'];
			//die;
			return $this->redirect(['view', 'id' => $max['MAX(product_id)']+1]);
        } else {
            return $this->render('create', [
			    'langs' => $langs,
                'model' => $model,
                'categories' => $categories,
            ]);
        }
    }

    /**
     * Updates an existing Product model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
   /* public function actionUpdate($id)
    {
        $categories = Category::find()->all();
        $model = $this->findModel($id);
        //echo '<pre>'.print_r($model,true).'</pre>';
        if ($model->load(Yii::$app->request->post())) {//die;
            //echo '<pre>'.print_r($model,true).'</pre>';
			$model = Product::find()->where(['product_id'=> $id])->all();
			echo '<pre>'.print_r($model,true).'</pre>';
			return $this->redirect(['view', 'id' => $model->id]);
        } else {
			//$model = Product::find()->where(['product_id'=> $id])->all();
            return $this->render('update', [
                'model' => $model,
                'categories' => $categories,
            ]);
        }
    }*/
public function actionUpdate($id)
    {
        $categories = Category::find()->all();
        $model = $this->findModel($id);
		//print_r($model);
		$langs = array(1 => array('lang_id'=>1,'name'=>'English'),2 => array('lang_id'=>2,'name'=>'Russian'),3 => array('lang_id'=>3,'name'=>'Ukrainian'));
		$all_news = Product::find()->where(['product_id'=> $id])->IndexBy('lang_id')->all();
        
	    //print_r($all_news);
		//echo '<pre>'.print_r($all_news,true).'</pre>';
		for($i = 1;$i<count($langs)+1;$i++):
		//echo $langs[$i]['id'];
		//echo $all_news[$i]['lang_id'];
		//if($langs[$i]['id'] == $all_news[$i]['lang_id']):
		//die;
		//echo $all_news[$i]['id'];
		//echo $all_news[$i]['lang_id'];
		$langs[$i]['id'] = $all_news[$i]['id'];
		//$langs[$i]['lang_id'] = $all_news[$i]['lang_id'];
		//echo $langs[$i]['lang_id'];
		$langs[$i]['title'] = $all_news[$i]['title'];
		$langs[$i]['description'] = $all_news[$i]['description'];
		//endif;
		endfor;
		//echo $model->slug;
		//echo $model->slug1;
		//echo $model->slug2;
		//print_r($langs);
		//echo '<pre>'.print_r($model,true).'</pre>';
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {//die;
            //echo '<pre>'.print_r($model,true).'</pre>';
			$model->UpdateNews($model->slug);
			//echo '<pre>'.print_r($model,true).'</pre>';
			//$model = Product::find()->where(['product_id'=> $id])->all();
			//echo '<pre>'.print_r($model,true).'</pre>';
			return $this->redirect(['view', 'id' => $model->product_id]);
        } else {
			//$model = Product::find()->where(['product_id'=> $id])->all();
            return $this->render('update', [
			    'langs' => $langs,
			    'all_news' => $all_news,
                'model' => $model,
                'categories' => $categories,
            ]);
        }
    }
    /**
     * Deletes an existing Product model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
		//echo $id;die;
		Product::deletePost($id);
        //$this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Product model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Product the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Product::find()->where(['product_id'=> $id])->one()) !== null) {    
			return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
