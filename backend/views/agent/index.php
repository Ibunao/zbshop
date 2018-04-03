<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\search\AgentSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '代理商管理';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="agent-user-model-index">

    <!-- <h1><?= Html::encode($this->title) ?></h1> -->
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

<!--     <p>
        <?= Html::a('Create Agent User Model', ['create'], ['class' => 'btn btn-success']) ?>
    </p> -->

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            // 'id',
            'username',
            'mobile',
            [
                'label' => '性别',
                'value' => function ($model)
                {
                    return $model->sex == 1? '男' : '女';
                }
            ],
            'age',
            [
                'attribute' => 'status',
                'label' => '审核状态',
                'value' => function($model) {
                    if ($model->status == 1) {
                        return '待审核';
                    }
                    if ($model->status == 2) {
                        return '审核通过';
                    }
                    if ($model->status == 3) {
                        return '拒绝';
                    }
                },
                'filter' => [
                    1 => '待审核',
                    2 => '审核通过',
                    3 => '拒绝',
                ]
            ],
            //'password',
            // 'idcard_img1',
            // 'idcard_img2',
            // 'bankcard',
            //'bank',
            //'area',
            //'status',
            //'openid',
            //'unionid',
            //'created_at',
            //'updated_at',

            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{view}',//{update}
                'header' => '查看详情',
            ],
        ],
    ]); ?>
</div>
