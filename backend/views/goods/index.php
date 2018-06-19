<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\search\GoodsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Goods Models';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="goods-model-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Goods Model', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'c_id',
            'g_id',
            'name',
            'spec',
            //'wx_price',
            //'market_price',
            //'stores',
            //'barcode',
            //'image',
            //'desc',
            //'limit',
            //'location',
            //'is_bill',
            //'is_repair',
            [
                'attribute' => 'is_on',
                'label' => '上架状态',
                'format' => 'raw',
                'value' => function($model) {
                    return $model->is_on == 0 ? '<span style = "color:red;">下架</span>' : '上架';
                },
                'filter' => [
                    0 => '下架',
                    1 => '上架'
                ]
            ],
            //'created_at',
            //'updated_at',

            [
                'class' => 'yii\grid\ActionColumn',
                // 'template' => '{view} {delete}',
                'template' => '{delete}',
                'header' => '操作',
                'buttons' => [
                    // 'view' => function ($url, $model, $key) { 
                    //     return Html::a('测试按钮', $url，['data-method' => 'post','data-pjax'=>'0']); 
                    // },
                    'delete'=> function ($url, $model, $key){
                        $str = $model->is_on ? '下架' : '上架';
                        return Html::a($str, ['delete', 'id'=>$model->id],[
                            'data-method'=>'post',              //POST传值
                            'data-confirm' => "确定{$str}该产品？", //添加确认框
                        ] ) ;
                    }
                ],
            ],
        ],
    ]); ?>
</div>
