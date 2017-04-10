<?php

use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use pjhl\multilanguage\LangHelper;

/* @var $this yii\web\View */
/* @var $model common\models\Product */
/* @var $form yii\widgets\ActiveForm */
?>
<?//$langs = array(1 => array('id'=>1,'name'=>'English'),2 => array('id'=>2,'name'=>'Russian'),3 => array('id'=>3,'name'=>'Ukrainian'));?>
<?//print_r($model);?>
<div class="tabs_menu">
<?//print_r($langs);?>
<?php foreach($langs as $key => $lang):?>
<a class="a_link" href="#tab<?=$lang['lang_id'];?>"><?=$lang['name']?></a>
<?php endforeach;?>
</div>
<?//print_r($model);?>
<?php foreach($langs as $key => $lang):?>
<?php if($lang['lang_id'] == 1){?>
<div class="tab" id = "tab<?=$lang['lang_id']?>">
    <?php $form = ActiveForm::begin(); ?>
   <?=$lang['name']?>
   <?= $form->field($model, "news_translate[".$key."][id]")->hiddenInput(['value' => $lang['id'] ])->label(false) ?>
   <?= $form->field($model, "news_translate[".$key."][lang_id]")->hiddenInput(['value' => "{$lang['lang_id']}" ])->label(false) ?>
	<?= $form->field($model, "news_translate[".$key."][title]")->textInput(['value' => $lang['title']]) ?>
    <?= $form->field($model, "news_translate[".$key."][description]")->textarea(['value' => $lang['description']]) ?>
</div>
<?php }?>
<?php if($lang['lang_id'] > 1) {?>
<div class="tab" id = "tab<?=$lang['lang_id']?>" style="display:none;">
<?=$lang['name']?>
<?= $form->field($model, "news_translate[".$key."][id]")->hiddenInput(['value' => $lang['id'] ])->label(false) ?>
   <?= $form->field($model, "news_translate[".$key."]['lang_id']")->hiddenInput(['value' => "{$lang['lang_id']}" ])->label(false) ?>
	<?= $form->field($model, "news_translate[".$key."][title]")->textInput(['value' => $lang['title']]) ?>
    <?= $form->field($model, "news_translate[".$key."][description]")->textarea(['value' => $lang['description']]) ?>
</div>
<?php }?>
<?php endforeach;?>  
<?= $form->field($model, 'category_id')->dropDownList(ArrayHelper::map($categories, 'id', 'title'), ['prompt' => 'Select category']) ?>

<?= $form->field($model, 'price')->textInput(['maxlength' => 19]) ?> 
    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

