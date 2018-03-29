<?php $this->beginBlock('endheader'); ?>
<style type="text/css">
	.align-center{
		margin: 300px 0px;
	}
</style>
<?php $this->endBlock(); ?>
<?php if ($isJion): ?>
<img id="dynamic" style="width:100%" src="<?=$src ;?>">
<b style="width:130px" class="center-block" >长按图片保存到本地</b>
<?php else: ?>
<div class="align-center">
<button id="jionus" type="button" class="btn btn-primary center-block">加入我们</button>
</div>
<?php endif; ?>

<?php $this->beginBlock('endbody'); ?>

<script>
	$("#jionus").bind("click",function(){
	  	$.get("/site/get-share-image",function(data, status){
	  		if (status == 'success') {
	  			// 获取图片成功
	  			if (data.code == 200) {
	  				var img = $('<img id="dynamic" style="width:100%">');
					img.attr('src', data.imgUrl);
					img.prependTo($('.container')[0]);
					$('<b style="width:130px" class="center-block" >长按图片保存到本地</b>').replaceAll(".align-center");
	  			}else{
	  				alert('获取失败，请公众号内留言反馈');
	  			}
	  		}
    });
	});
</script>

<?php $this->endBlock(); ?>
