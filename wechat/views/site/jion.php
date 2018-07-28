<?php $this->beginBlock('endheader'); ?>
<style type="text/css">
	.align-center{
		margin: 300px 30px;
	}
	.margin-top{
		margin-top: 30px;
	}
</style>
<?php $this->endBlock(); ?>
<?php if ($isJion): ?>
<img id="dynamic" style="width: 35%;position: absolute;left: 33%;top: 20%;" src="<?=$src ;?>">
<!-- <b style="width:130px" class="center-block" >长按图片保存到本地</b> -->
<?php else: ?>
<div class="align-center">
<?php if ($status == 2): ?>
	<button id="jionus" type="button" class="btn btn-primary center-block">申请专属邀请码</button>
<?php elseif ($status == 1): ?>
	<button type="button" class="btn btn-warning center-block">审核中</button>
<?php else: ?>
	<button type="button" class="btn btn-danger center-block">申请被拒</button>
	<button id="signup" type="button" class="btn btn-primary center-block margin-top">重新提交信息</button>
<?php endif ?>

</div>
<?php endif; ?>

<?php $this->beginBlock('endbody'); ?>

<script>
	$("#jionus").bind("click",function(){
	  	$.get("/site/get-share-image",function(data, status){
	  		if (status == 'success') {
	  			// 获取图片成功
	  			if (data.code == 200) {
	  				var img = $('<img id="dynamic" style="width: 35%;position: absolute;left: 33%;top: 20%;">');
					img.attr('src', data.imgUrl);
					img.prependTo($('.container')[0]);
					$('.wrap').css({"background-image":"url(/images/helpers/dailibeijing.jpg)", "background-repeat":"no-repeat", "background-size":"100% 100%","-moz-background-size":"100% 100%"})
					$('#jionus').hide();
					// $('<b style="width:130px" class="center-block" >长按图片保存到本地</b>').replaceAll(".align-center");
	  			}else{
	  				alert('获取失败，请公众号内留言反馈');
	  			}
	  		}
    });
	});
	$("#signup").bind("click",function(){
	  	location = '/site/signup';
	});
	<?php if ($isJion): ?>
		$('.wrap').css({"background-image":"url(/images/helpers/dailibeijing.jpg)", "background-repeat":"no-repeat", "background-size":"100% 100%","-moz-background-size":"100% 100%"})
	<?php endif ?>
</script>

<?php $this->endBlock(); ?>
