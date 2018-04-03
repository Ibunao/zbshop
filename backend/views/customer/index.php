<?php

use yii\helpers\Html;
use yii\grid\GridView;
use common\models\AgentUserModel;
/* @var $this yii\web\View */
/* @var $searchModel backend\models\search\CustomerSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '代理拉用户统计';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="customer-model-index">

    <!-- <h1><?= Html::encode($this->title) ?></h1> -->
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <!-- <p>
        <?= Html::a('Create Customer Model', ['create'], ['class' => 'btn btn-success']) ?>
    </p> -->

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            // 'username',
            // 'mobile',
            // 'password',
            // 'openid',
            //'unionid',
            // 'share_id',
            [
                'attribute' => 'share_id',
                'label' => '代理人',
                'value' => function($model) {
                    return $model->agentName;
                },
                'filter' => AgentUserModel::getAgents(),
            ],
            [
                'attribute' => 'created_at',
                'label'=>'创建时间',
                'value'=>function ($model)
                    {
                       return date("Y-m-d H:i:s", $model->created_at);
                    }
            ],
            //'updated_at',

            // ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
