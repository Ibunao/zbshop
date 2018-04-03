<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\search\AgentSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="agent-user-model-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'username') ?>

    <?= $form->field($model, 'mobile') ?>

    <?= $form->field($model, 'sex') ?>

    <?= $form->field($model, 'age') ?>

    <?php // echo $form->field($model, 'password') ?>

    <?php // echo $form->field($model, 'idcard_img1') ?>

    <?php // echo $form->field($model, 'idcard_img2') ?>

    <?php // echo $form->field($model, 'bankcard') ?>

    <?php // echo $form->field($model, 'bank') ?>

    <?php // echo $form->field($model, 'area') ?>

    <?php // echo $form->field($model, 'status') ?>

    <?php // echo $form->field($model, 'openid') ?>

    <?php // echo $form->field($model, 'unionid') ?>

    <?php // echo $form->field($model, 'created_at') ?>

    <?php // echo $form->field($model, 'updated_at') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
