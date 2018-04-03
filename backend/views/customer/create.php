<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\CustomerModel */

$this->title = 'Create Customer Model';
$this->params['breadcrumbs'][] = ['label' => 'Customer Models', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="customer-model-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
