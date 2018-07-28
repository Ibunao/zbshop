<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\WxOther */

$this->title = '更新';
$this->params['breadcrumbs'][] = ['label' => '图文消息', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="wx-other-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
