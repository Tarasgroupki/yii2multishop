<?php

namespace backend\controllers;

use backend\models\MultipleUploadForm;
use common\models\Product;
use Yii;
use common\models\Image;
use backend\models\ImageSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;

/**
 * ImageController implements the CRUD actions for Image model.
 */
class ImageController extends Controller
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
     * Lists all Image models.
     * @param int $id product id
     * @return mixed
     *
     * @throws NotFoundHttpException
     */
    public function actionIndex($id)
    {
		//echo $id;
		$product_id = Yii::$app->request->get('product_id');
        if (!Product::find()->where(['id' => $id])->exists()) {
            throw new NotFoundHttpException();
        }
        $this->enableCsrfValidation = false;
        $form = new MultipleUploadForm();
       
        $searchModel = new ImageSearch();
        $searchModel->product_id = $id;
    
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        if (Yii::$app->request->isPost) {
			if(!Image::find()->where(['product_id' => $id])->exists()){
            $form->files = UploadedFile::getInstances($form, 'files');

            if ($form->files && $form->validate()) {
               Yii::$app->session->setFlash('success','Зображення успішно завантажено!');               
			   echo Yii::$app->session->getFlash('success');
			   foreach ($form->files as $file) {
                    $image = new Image();
                    $image->product_id = $id;
					$image->prod_id = $product_id;
                    if ($image->save()) {
                        $file->saveAs($image->getPath());
                    }
                }
            }
        }
		else
		{
			Yii::$app->session->setFlash('warning','Помилка!');
			echo Yii::$app->session->getFlash('warning');
		}
	}	

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'uploadForm' => $form,
        ]);
    }

    /**
     * Deletes an existing Image model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {//die;
	    Yii::$app->session->setFlash('success','Зображення успішно видалено!');
	   // echo Yii::$app->session->getFlash('success');
		$prod_id = Yii::$app->request->get('product_id');
        //echo $prod_id;//die;
		$image = $this->findModel($id);
		$this->enableCsrfValidation = false;
        $productId = $image->product_id;
		$prod_id = $image->prod_id;
        $image->delete();
        return $this->redirect(['index', 'id' => $productId,'product_id' => $prod_id]);
    }

    /**
     * Finds the Image model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Image the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {//echo $id;
        if (($model = Image::find()->where(['product_id'=>$id])->one()) !== null) {
           // print_r($model);
		   $this->redirect(['image/index']);
			return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
