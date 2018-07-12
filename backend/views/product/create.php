<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\GoodsSpecificationsModel */

$this->title = 'Create Goods Specifications Model';
$this->params['breadcrumbs'][] = ['label' => 'Goods Specifications Models', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="goods-specifications-model-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
