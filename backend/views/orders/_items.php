<?php
use yii\helpers\Html;
use yii\helpers\HtmlPurifier;
?>
<style type="text/css">
	.item{
		width:300px;
		height: 200px;
		border:3px solid gray;
		box-shadow:20px 20px 100px 3px gray;
		border-radius: 30px;
		padding: 30px;
	}
</style>
<div class="item">
  <h4 style="font-weight:bold"><?= Html::encode($model['name']) ?></h4>
  <div style="margin:15px 0">
      <!-- <img style="width:100%;" src="<?=$model['image'] ;?>"> -->
  </div>
    
  <p class="info">
    规格：<?= $model['specvalue'] ?><br>
    数量：<?= $model['num'] ?>
  </p>
</div>
