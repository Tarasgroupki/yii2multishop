<?php

use yii\helpers\Html;
use frontend\widgets\Alert;
/* @var $this yii\web\View */
/* @var $model common\models\Product */

$this->title = 'Update Product: ' . ' ' . $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Products', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->title, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="product-update">
<?= Alert::widget() ?>
    <h1><?= Html::encode($this->title) ?></h1>
<?//print_r($model);?>
    <?= $this->render('_form', [
	    'langs' => $langs,
	    'all_news' => $all_news,
        'model' => $model,
        'categories' => $categories,
    ]) ?>

</div>
