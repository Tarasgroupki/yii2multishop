<?php
use yii\helpers\Html;
/* @var $this yii\web\View */
?>
<?php //print_r($items);?>
<?php //foreach($items as $item):?>
<?=Html::img($items['image_url']);?>
<?php echo '<br />';?>
<?php foreach($photos as $photo):?>
<?=Html::img('/img/gallery/'.$photo['gallery_name'].'/Thumb/'.$photo['name']);?>
<?php endforeach;?>
<h1><?=$items['title'];?></h1>
<h4><?=$items['description'];?></h4>
<div class="col-xs-2">
            <?php
			echo $quantity;
			?>

            <?= Html::a('-', ['cart/update1', 'id' => $items->product_id, 'quantity' => $quantity - 1], ['class' => 'btn btn-danger', 'disabled' => ($quantity - 1) < 1])?>
            <?= Html::a('+', ['cart/update1', 'id' => $items->product_id, 'quantity' => $quantity + 1], ['class' => 'btn btn-success'])?>
        </div>
<button  type="button" title="Add to Cart" class="button btn-cart"> <span> <span><?= Html::a(\Yii::t('app', 'Add to cart'), ['cart/add', 'id' => $items->id], ['class' => 'button btn-cart'])?></span> </span> </button>
<?//endforeach;?>
<iframe width="600" height="450" frameborder="0" style="border:0"
src="https://www.google.com/maps/embed/v1/place?q=%D0%B2%D1%83%D0%BB%D0%B8%D1%86%D1%8F%20%D0%93%D0%B0%D0%BB%D0%B8%D1%86%D1%8C%D0%BA%D0%B0%2024%D0%92%2C%D0%86%D0%B2%D0%B0%D0%BD%D0%BE-%D0%A4%D1%80%D0%B0%D0%BD%D0%BA%D1%96%D0%B2%D1%81%D1%8C%D0%BA%D0%B0%20%D0%BE%D0%B1%D0%BB%D0%B0%D1%81%D1%82%D1%8C%2C%20%D0%A3%D0%BA%D1%80%D0%B0%D1%97%D0%BD%D0%B0&key=AIzaSyD2zdYTuRyFovJli9PVX1Ug8JkuoQwBzwk" allowfullscreen></iframe>