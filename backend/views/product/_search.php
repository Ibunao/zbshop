<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\search\GoodsSpecificationsSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="goods-specifications-model-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'g_id') ?>

    <?= $form->field($model, 'sids') ?>

    <?= $form->field($model, 'snames') ?>

    <?= $form->field($model, 's_v_value') ?>

    <?php // echo $form->field($model, 's_v_ids') ?>

    <?php // echo $form->field($model, 'image') ?>

    <?php // echo $form->field($model, 'price') ?>

    <?php // echo $form->field($model, 'barcode') ?>

    <?php // echo $form->field($model, 'store') ?>

    <?php // echo $form->field($model, 'disabled') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
