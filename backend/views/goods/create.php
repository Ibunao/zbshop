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
  <div id="attr-row" class="row">
   <label class="col-sm-2 control-label" for="#">商品属性(选填)</label>
   <div id="append-attr" class="row col-sm-10">
<!--      <div class="col-sm-2  col-md-3 border-style form-inline">
      <label for="add-attr-input">登山</label>
      <input id="input-add" class="form-control" placeholder="输入属性值" type="text" name="add-attr-input">
     </div>
 -->

   </div>
   <div class="col-sm-offset-2">
     <div class="col-sm-2  col-md-3 border-style form-inline">
      <label for="add-attr-input">添加新属性</label>
      <input id="input-add" class="form-control" placeholder="输入属性名" type="text" name="add-attr-input">
      <button id="add-attr" class="btn btn-default">添加</button>
     </div>
   </div>
  </div>
</div>
<div class="row">
   <label class="col-sm-2 control-label" for="category">选择分组</label>
   <div class="col-sm-3">
     <?php foreach ($groups as $key => $value): ?>
       <label class="checkbox-inline">
        <input type="checkbox" name="<?='groups['.$key.']' ;?>" value="<?=$key ;?>"> <?=$value ;?>
      </label>
     <?php endforeach ?>
   </div>
  </div>

<?php $this->beginBlock('endbody'); ?>

<script>
  var attrDom = '<div class="col-sm-2  col-md-3 border-style form-inline"><label for="attrs[replaceid]">replacename</label><input class="form-control" placeholder="输入属性值" type="text" name="attrs[replaceid]"></div>';
	$("#category").change(function (that) {
		var cid = $("#category").val();
		if (cid) {
			var url = '/goods/get-attrs';
      var data = {cid : cid};
			ajaxRequest(url, data, function (result) {
        if (result.code == 200) {
          console.log(result.other);
          for (var key in result.other) {
            replaceDom = attrDom.replace(/replaceid/g, key);
            replaceDom = replaceDom.replace(/replacename/g, result.other[key]);
            $(replaceDom).appendTo($("#append-attr"));
          }
       }
      });
		}
	})
  $("#add-attr").bind('click', function () {
    var attr = $('#input-add').val();
    var cateid = $('#category option:selected').val();

    if (cateid == 0) {
      alert('请先选择分类');
      return;
    }
    if (attr) {
      var url = '/goods/set-attr';
      var data = {attr: attr, cateid: cateid};
      ajaxRequest(url, data, function (result) {
        if (result.code == 200) {
          for (var key in result.other) {
            replaceDom = attrDom.replace(/replaceid/g, key);
            replaceDom = replaceDom.replace(/replacename/g, result.other[key]);
            $(replaceDom).appendTo($("#append-attr"));
          }
        }else{
          alert(result.msg);
        }
      });
    }
  })
  function ajaxRequest(url, data, func, dataType = 'json', type = 'GET') {
    $.ajax({
        url: url,
        data: data,
        type: type, //'POST'
        // success: function (data) {
        //    return data;
        // },
        success: func,
        dataType: dataType
      });
  }
</script>

<?php $this->endBlock(); ?>