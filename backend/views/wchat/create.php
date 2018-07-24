<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\WxOther */

$this->title = '创建';
$this->params['breadcrumbs'][] = ['label' => '创建', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="wx-other-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
