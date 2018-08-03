<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\search\WxOtherSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '图文消息';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="wx-other-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('创建', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'title',
            'desc',
            // 'url:url',
            'img',
            'order',
            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
