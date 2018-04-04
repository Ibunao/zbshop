<?php $this->beginBlock('endheader'); ?>
<style type="text/css">

</style>
<?php $this->endBlock(); ?>

<div class="form-group">
   <label class="col-sm-3 control-label" for="category">选择分类</label>
   <div class="col-sm-3">
     <select name="category" id="category" class="selectpicker show-tick form-control"  data-width="98%" data-first-option="true" title='请选择商品分类' required data-live-search="true">
     	<option value="0">请选择商品分类</option>
     	<?php foreach (Yii::$app->params['categories'] as $key => $value): ?>
        	<option value="<?=$key ;?>"><?=str_repeat('--', $value['level'] - 1).$value['name'] ;?></option>
     	<?php endforeach ?>
     </select>
   </div>
   <!-- <label class="col-sm-3 control-label" for="#">商品属性(选填)</label>
   <div class="col-sm-6">
	 <label></label>
     <select name="attribute" id="attribute" class="selectpicker show-tick form-control"  data-width="98%" data-first-option="false" title='商品属性(必选)' required data-live-search="true">
     	<?php foreach (Yii::$app->params['categories'] as $key => $value): ?>
        	<option value="<?=$key ;?>"><?=str_repeat('--', $value['level'] - 1).$value['name'] ;?></option>
     	<?php endforeach ?>
     </select>
   </div> -->
</div>


<?php $this->beginBlock('endbody'); ?>

<script>
	$("#category").change(function (that) {
		var cid = $("#category").val();
		if (cid) {
			var url = '/goods/get-attrs';
			$.ajax({
			  url: url,
			  data: {cid : cid},
			  success: function (data) {
			  	 console.log(data);
			  },
			  dataType: json
			});
		}
	})
</script>

<?php $this->endBlock(); ?>