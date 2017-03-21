<?php
use yii\helpers\Html;
use yii\widgets\ListView;
use yii\widgets\Menu;
use dosamigos\transliterator\TransliteratorHelper;
use pjhl\mltilanguage\LangHelper;
/* @var $this yii\web\View */
$title = $category === null ? 'Welcome!' : $category->title;
$this->title = Html::encode($title);
?>

<h1><?= Html::encode($title) ?></h1>

<div class="container-fluid">
  <div class="row">
      <div class="col-xs-4">
          <?= Menu::widget([
              'items' => $menuItems,
              'options' => [
                  'class' => 'menu',
              ],
          ]) ?>
      </div>
	  <?//LangHelper::getLanguage('id');?>
	  <?//print_r(Yii::$app->params)?>
      <div class="col-xs-8">
          <?= ListView::widget([
              'dataProvider' => $productsDataProvider,
              'itemView' => '_product',
          ]) ?>
      </div>
  </div>
</div>