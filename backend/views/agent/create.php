<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\AgentUserModel */

$this->title = 'Create Agent User Model';
$this->params['breadcrumbs'][] = ['label' => 'Agent User Models', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="agent-user-model-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
