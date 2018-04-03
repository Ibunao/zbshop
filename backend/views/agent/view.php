<?php
use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\AgentUserModel */

$this->title = $model->username;
$this->params['breadcrumbs'][] = ['label' => '代理商管理', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="agent-user-model-view">

    <!-- <h1><?= Html::encode($this->title) ?></h1> -->

    <p>
        <?= Html::a('通过', ['update', 'id' => $model->id, 'status' => 2], ['class' => 'btn btn-primary']) ?>
         <?= Html::a('拒绝', ['update', 'id' => $model->id, 'status' => 3], [
            'class' => 'btn btn-danger',
            // 'data' => [
            //     'confirm' => 'Are you sure you want to delete this item?',
            //     'method' => 'post',
            // ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            // 'id',
            'username',
            'mobile',
            'sex',
            'age',
            // 'password',
            [
                // 'attribute' => 'idcard_img1',
                'format' => 'raw',
                'label'=>'身份证正面图片',
                'value'=>function ($model)
                    {
                       return Html::img(Yii::$app->params['wechatUrl'].$model->idcard_img1, ['width' => '400px']);
                    }
            ],
            [
                'format' => 'raw',
                'label'=>'身份证正面图片',
                'value'=>function ($model)
                    {
                       return Html::img(Yii::$app->params['wechatUrl'].$model->idcard_img2, ['width' => '400px']);
                    }
            ],
            'bankcard',
            'bank',
            'area',
            [
                'label'=>'审核状态',
                'value'=>function ($model)
                    {
                       if ($model->status == 2) {
                           return '通过';
                       }elseif ($model->status == 3) {
                           return '拒绝';
                       }
                       return '待审核';
                    }
            ],
            // 'openid',
            // 'unionid',
            [
                'label'=>'创建时间',
                'value'=>function ($model)
                    {
                       return date("Y-m-d H:i:s", $model->created_at);
                    }
            ],
            // 'updated_at',
        ],
    ]) ?>

</div>
