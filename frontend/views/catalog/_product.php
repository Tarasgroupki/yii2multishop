<?php
use yii\helpers\Html;
use yii\helpers\Markdown;
use dosamigos\transliterator\TransliteratorHelper;
use yii\helpers\Url;
?>
<?php /** @var $model \common\models\Product */ ?>
<div class="col-xs-12 well">
    <div class="col-xs-2">
        <?php
        $images = $model->images;
        if (isset($images[0])) {
            echo Html::img($images[0]->getUrl(), ['width' => '100%']);
        }//print_r($images);die;
        ?>
    </div>
    <div class="col-xs-6"> 
        <a href="<?=Url::toRoute(['view','id' => $model->product_id, 'name' => $model->slug]);?>"><h2><?= Html::encode($model->title) ?></h2></a>  
		<?= Markdown::process($model->description) ?>
    </div>

    <div class="col-xs-4 price">
        <div class="row">
            <div class="col-xs-12">$<?= $model->price ?></div>
            <div class="col-xs-12"><?= Html::a(\Yii::t('app', 'Add to cart'), ['cart/add', 'id' => $model->id], ['class' => 'btn btn-success'])?></div>
        </div>
    </div>
</div>