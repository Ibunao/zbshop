<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\GoodsSpecificationsModel */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="goods-specifications-model-form">

    <?php $form = ActiveForm::begin(); ?>

    <!-- <?= $form->field($model, 'g_id')->textInput() ?>

    <?= $form->field($model, 'sids')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'snames')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 's_v_value')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 's_v_ids')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'image')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'price')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'barcode')->textInput() ?> -->

    <?= $form->field($model, 'store')->textInput(['maxlength' => true]) ?>

    <!-- <?= $form->field($model, 'disabled')->textInput() ?> -->

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
