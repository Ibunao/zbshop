<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\AgentUserModel */

$this->title = 'Update Agent User Model: {nameAttribute}';
$this->params['breadcrumbs'][] = ['label' => 'Agent User Models', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="agent-user-model-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
