<?php $this->beginBlock('endheader'); ?>
<style type="text/css">
  .border-style{
    border:1px dotted grey;
    margin:5px;
    padding: 5px;
  }
</style>
<?php $this->endBlock(); ?>

<div class="form-group">
  <div class="row">
   <label class="col-sm-2 control-label" for="category">选择分类</label>
   <div class="col-sm-3">
     <select name="category" id="category" class="selectpicker show-tick form-control"  data-width="98%" data-first-option="true" title='请选择商品分类' required data-live-search="true">
     	<option value="0">请选择商品分类</option>
     	<?php foreach (Yii::$app->params['categories'] as $key => $value): ?>
        	<option value="<?=$key ;?>"><?=str_repeat('--', $value['level'] - 1).$value['name'] ;?></option>
     	<?php endforeach ?>
     </select>
   </div>
  </div>
  <div class="row">
   <label class="col-sm-2 control-label" for="#">商品属性(选填)</label>
   <div class="col-sm-3 border-style">
    <input id="input-add" class="form-control" placeholder="输入属性名" type="text" name="add">
    <button id="add-attr" class="btn btn-default">添加</button>
   </div>
  </div>
</div>


<?php $this->beginBlock('endbody'); ?>

<script>
	$("#category").change(function (that) {
		var cid = $("#category").val();
		if (cid) {
			var url = '/goods/get-attrs';
      var data = {cid : cid};
			ajaxRequest(url, data);
		}
	})
  $("#add-attr").bind('click', function () {
    var val = $('#input-add').val();
    if (val) {
      var url = '/goods/set-attr';
      var data = {attr: val};
      var result = ajaxRequest(url, data);
      console.log(result);
    }
  })
  function ajaxRequest(url, data, dataType = 'json', type = 'GET') {
    $.ajax({
        url: url,
        data: data,
        type: type, //'POST'
        success: function (data) {
           // console.log(data);
           return data;
        },
        dataType: dataType
      });
  }
</script>

<?php $this->endBlock(); ?>