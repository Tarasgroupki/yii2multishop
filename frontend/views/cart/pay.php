<?php
use frontend\widgets\RenderForm;

$invoiceId = 4;
?>
<?echo RenderForm::widget(['api' => Yii::$app->pm,
   'invoiceId' => $invoiceId,
   'amount' => $amount,
   'description' => 'Пополнение внутреннего счета',
   'autoRedirect' => true,]);?>