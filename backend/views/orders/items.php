<?php

use yii\helpers\Html;
use yii\widgets\ListView;

/* @var $this yii\web\View */
/* @var $model common\models\OrdersModel */

$this->title = $orderId;
$this->params['breadcrumbs'][] = ['label' => '订单列表', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="orders-model-view">

    <h1><?= Html::encode('订单号：'.$this->title) ?></h1>
    <?= ListView::widget([
            'dataProvider' => $dataProvider,
            'itemView' => '_items',
        ]) ?>

</div>

