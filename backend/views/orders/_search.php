<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\search\OrdersSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="orders-model-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'openid') ?>

    <?= $form->field($model, 'order_id') ?>

    <?= $form->field($model, 'pay_id') ?>

    <?= $form->field($model, 'price') ?>

    <?php // echo $form->field($model, 'express_fee') ?>

    <?php // echo $form->field($model, 'integrals') ?>

    <?php // echo $form->field($model, 'deduction') ?>

    <?php // echo $form->field($model, 'pay_price') ?>

    <?php // echo $form->field($model, 'status') ?>

    <?php // echo $form->field($model, 'ship_status') ?>

    <?php // echo $form->field($model, 'ship_no') ?>

    <?php // echo $form->field($model, 'ship_type') ?>

    <?php // echo $form->field($model, 'remark') ?>

    <?php // echo $form->field($model, 'name') ?>

    <?php // echo $form->field($model, 'mobile') ?>

    <?php // echo $form->field($model, 'address') ?>

    <?php // echo $form->field($model, 'create_at') ?>

    <?php // echo $form->field($model, 'update_at') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
