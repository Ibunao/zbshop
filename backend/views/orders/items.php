<?php

use yii\helpers\Html;
use yii\widgets\ListView;

/* @var $this yii\web\View */
/* @var $model common\models\OrdersModel */

$this->title = $orderId;
$this->params['breadcrumbs'][] = ['label' => '订单列表', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<style type="text/css">
	#orderinfo{
		margin:30px;

	}
</style>
<div class="orders-model-view">

    <h1><?= Html::encode('订单号：'.$this->title) ?></h1>
    <?= ListView::widget([
            'dataProvider' => $dataProvider,
            'itemView' => '_items',
        ]) ?>
	
</div>

<div id="orderinfo">
	<?php if ($wuliu) : ;?>

	<div>
		<label>快递编号：</label><input type="text" name="快递编号" id="kuaidicode"><br/>
		<label>快递类型：</label><input type="text" name="快递类型" id="kuaiditype"><br/>
		<button onclick="submit_kuaidi()">确认</button>
	</div>
	<?php else: ;?>
	<div>
		<button onclick="submit_tuikuan()">确认退款</button>
	</div>
	<?php endif ;?>
</div>
<?php $this->beginBlock('endbody') ?>
<script>

	function submit_kuaidi() {
		var code = document.getElementById('kuaidicode').value;
		var type = document.getElementById('kuaiditype').value;
		var request=new XMLHttpRequest()
		request.open("GET","/orders/kuaidi?"+"code="+code+"&type="+type+"&orderid="+"<?=$orderId ;?>",true);
		request.send();
		request.onreadystatechange=function(data){
			if(request.readyState===4&&request.status===200){
				console.log(request.response);
				var result = JSON.parse(request.response)
				if (result.code == 200) {
					alert('添加成功');
					location.reload();
				}else{
					alert(result.msg);
				}
			}
		}
	}
	function submit_tuikuan() {
		var request=new XMLHttpRequest()
		request.open("GET","/orders/tuikuan?orderid="+"<?=$orderId ;?>",true);
		request.send();
		request.onreadystatechange=function(data){
			if(request.readyState===4&&request.status===200){
				console.log(request.response);
				var result = JSON.parse(request.response)
				if (result.code == 200) {
					alert('确认成功');
					location.reload();
				}else{
					alert(result.msg);
				}
			}
		}
	}
</script>
<?php $this->endBlock() ?>

