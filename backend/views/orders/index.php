<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\search\OrdersSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '订单列表';
$this->params['breadcrumbs'][] = $this->title;
?>
<style type="text/css">
    #w0{
        overflow:scroll !important;
    }
</style>
<div class="orders-model-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <!-- <?= Html::a('Create Orders Model', ['create'], ['class' => 'btn btn-success']) ?> -->
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            // ['class' => 'yii\grid\SerialColumn'],

            'id',
            // 'openid',
            'order_id',
            // 'pay_id',
            'price',
            'express_fee',
            'integrals',
            'deduction',
            'pay_price',
            // 'status',
            [
                'attribute' => 'status',
                'format' => 'raw',
                'value' => function($model) {
                    return $model->status == 1 ? '待支付' : ($model->status == 2 ? '已支付' : ($model->status == 4 ? '<span style = "color:red;">支付金额异常</span>' : ''));
                },
                'filter' => [
                    1 => '待支付',
                    2 => '已支付',
                    4 => '支付异常',
                ]
            ],
            // 'ship_status',
            [
                'attribute' => 'ship_status',
                'format' => 'raw',
                'value' => function($model) {
                    return $model->ship_status == 0 ? '未发货' : ($model->ship_status == 1 ? '已发货' : ($model->ship_status == 2 ? '确认收货' : ($model->ship_status == 3 ? '退货' : ($model->ship_status == 5 ? '完成退货' : ''))));
                },
                'filter' => [
                    0 => '未发货',
                    1 => '已发货',
                    2 => '确认收货',
                    3 => '退货',
                    5 => '退货完成',
                ]
            ],
            'ship_no',
            'ship_type',
            'remark',
            'name',
            'mobile',
            'address',
            // 'create_at',
            [
                'attribute' => 'create_at',
                'value' => function($model) {
                    return date("Y-m-d H:i:s", $model->create_at);
                },
            ],
            //'update_at',

            [   
                'class' => 'yii\grid\ActionColumn', 
                'template' => '{view}',
                'buttons' => [
                    'view' => function ($url, $model, $key) { 
                        return Html::a('详情', ['view', 'orderId'=>$model->order_id],
                            [
                                'data-method' => 'post',
                                'data-pjax'=>'0'
                            ]); 
                    },
                ],
            ],
        ],
    ]); ?>
</div>
