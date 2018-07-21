<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\search\GoodsSpecificationsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '商品库存';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="goods-specifications-model-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <!-- <p>
        <?= Html::a('Create Goods Specifications Model', ['create'], ['class' => 'btn btn-success']) ?>
    </p> -->

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            // 'g_id',
            // 'sids',
            ['label'=>'商品名称',  'attribute' => 'goods_name',  'value' => 'goods_name' ],
            // 'snames',
            ['label'=>'规格名称',  'attribute' => 's_v_value',  'value' => 's_v_value' ],
            // 's_v_value',
            //'s_v_ids',
            //'image',
            //'price',
            //'barcode',
            'store',
            //'disabled',

            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{update}',
                'header' => '更新',
            ]
        ],
    ]); ?>
</div>
