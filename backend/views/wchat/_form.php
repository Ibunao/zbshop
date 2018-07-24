<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\WxOther */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="wx-other-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'url')->textInput(['maxlength' => true]) ?>
    <?= $form->field($model, 'order')->textInput(['maxlength' => true]) ?>

    <!-- <?= $form->field($model, 'img')->textInput(['maxlength' => true]) ?> -->
    <?= $form->field($model, 'imgFile')->fileInput() ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
