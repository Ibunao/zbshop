<?php 
$this->title = '添加商品';
 ?>
<?php $this->beginBlock('endheader'); ?>
<style type="text/css">
  .border-style{
    border:1px dotted grey;
    margin:5px;
    padding: 5px;
  }
  .row{
    margin-bottom: 10px;
  }
</style>
<?php $this->endBlock(); ?>

<form action="/goods/create" method="post">
<input name="_csrf" type="hidden" id="_csrf" value="<?= Yii::$app->request->csrfToken ?>">
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
     <button id="add-attr-button" type="button" class="btn btn-primary">添加新属性</button>
     <div hidden="hidden" class="col-sm-2  col-md-3 border-style form-inline">
      <label for="add-attr-input">添加新属性</label>
      <input id="input-add" class="form-control" placeholder="输入属性名" type="text" name="add-attr-input">
      <button id="add-attr" class="btn btn-default">添加</button>
     </div>
   </div>
  </div>
</div>
<div class="row">
   <label class="col-sm-2 control-label" for="category">选择分组(选填)</label>
   <div id="append-group" class="row col-sm-10">
     <?php foreach ($groups as $key => $value): ?>
     <div class="col-sm-2">
         <label class="checkbox-inline">
          <input type="checkbox" name="<?='groups['.$key.']' ;?>" value="<?=$key ;?>"> <?=$value['name'] ;?>
        </label>
     </div>
     <?php endforeach ?>
   </div>
   <div class="col-sm-offset-2">
    <button id="add-group-button" type="button" class="btn btn-primary">添加新分组</button>
     <div hidden="hidden" class="col-sm-2  col-md-3 border-style form-inline">
      <label for="add-group-input">添加新分组</label>
      <input class="form-control" placeholder="输入分组名" type="text" name="add-group-input">
      <button id="add-group" class="btn btn-default">添加</button>
     </div>
   </div>
</div>
<div class="row form-inline">
  <label class="col-sm-2 control-label" for="category">商品名称</label>
  <input type="text" class="form-control" name="goodsName" placeholder="商品名称">
</div>
<div class="row form-inline">
  <label class="col-sm-2 control-label" for="category">商品规格</label>
  <label class="radio-inline">
    <input type="radio" name="specification" id="inlineRadio1" value="0"> 单规格
  </label>
  <label class="radio-inline">
    <input type="radio" name="specification" id="inlineRadio2" value="1"> 多规格
  </label>
</div>
<div>
  <div class="row form-inline">
    <label class="col-sm-2 control-label" for="category">微信价(售价)</label>
    <input type="text" class="form-control" name="goodsWxPrice" placeholder="销售价格">
  </div>
  <div class="row form-inline">
    <label class="col-sm-2 control-label" for="category">原价(选填)</label>
    <input type="text" class="form-control" name="goodsOriPrice" placeholder="原价格">
  </div>
  <div class="row form-inline">
    <label class="col-sm-2 control-label" for="category">商品条码(选填)</label>
    <input type="text" class="form-control" name="goodsNo" placeholder="商品条码">
  </div>
  <div class="row form-inline">
    <label class="col-sm-2 control-label" for="category">库存</label>
    <input type="text" class="form-control" name="goodsStore" placeholder="库存">
  </div>
</div>
<div class="row form-inline">
  <label class="col-sm-2 control-label" for="category">商品图片</label>
  <div class="col-sm-offset-2">
  <h5>主图 (建议尺寸为640像素*640像素，大小不超过500kb) </h5>
  <input type="file" id="master-img" data-inputname = 'goodsMasterImg' data-classname = 'col-sm-2' />
  <div class="clearfix"></div>
  <h5 class="clearfix">其它图片(选传，单张图片大小不超过500kb，最多10张)</h5>
  <input type="file" id="other-img" multiple data-inputname = 'goodsOtherImg' data-classname = 'col-sm-2' />
  <div></div>
  </div>
</div>
<div class="row form-inline">
  <label class="col-sm-2 control-label" for="category">详情描述</label>
  <label class="radio-inline">
    <input type="radio" name="describe" value="0"> 不设置
  </label>
  <label class="radio-inline">
    <input type="radio" name="describe" value="1"> 文字
  </label>
  <label class="radio-inline">
    <input type="radio" name="describe" value="2"> 图片
  </label>
</div>
<div class="row">
  
<div id='describeindex' class="row col-sm-4 col-sm-offset-2">
  <textarea id="descText" style="display: none;" hidden="hidden" class="form-control" name="describeText" rows="6"></textarea>
  <div id="descImgDiv" hidden="hidden">
    <input type="file" id="desc-img" data-inputname = "destImg"  data-classname = "col-sm-12" multiple />
    <div></div>
  </div>
</div>
</div>
<div class="row form-inline">
  <label class="col-sm-2 control-label" for="category">是否限购</label>
  <label class="radio-inline">
    <input type="radio" name="limitation" value="0"> 不限购
  </label>
  <label class="radio-inline">
    <input type="radio" name="limitation" value="1"> 限购
  </label>
  <input type="text" class="form-control" name="limitationvalue">
</div>
<div class="row form-inline">
  <label class="col-sm-2 control-label" for="category">所在地</label>
  <input type="text" class="form-control" name="locationvaue" />
</div>
<div class="row form-inline">
  <label class="col-sm-2 control-label" for="category">发票</label>
  <label class="radio-inline">
    <input type="radio" name="bill" value="0"> 无
  </label>
  <label class="radio-inline">
    <input type="radio" name="bill" value="1"> 有
  </label>
</div>
<div class="row form-inline">
  <label class="col-sm-2 control-label" for="category">保修</label>
  <label class="radio-inline">
    <input type="radio" name="repair" value="0"> 无
  </label>
  <label class="radio-inline">
    <input type="radio" name="repair" value="1"> 有
  </label>
</div>
<div class="row form-inline">
  <label class="col-sm-2 control-label" for="category">上架</label>
  <label class="radio-inline">
    <input type="radio" name="putaway" value="0"> 暂不上架
  </label>
  <label class="radio-inline">
    <input type="radio" name="putaway" value="1"> 立即上架
  </label>
</div>
<button type="submit" class="btn btn-primary col-sm-offset-8">提交</button>
</form>
<?php $this->beginBlock('endbody'); ?>

<script>
  var attrDom = '<div class="col-sm-2  col-md-3 border-style form-inline"><label for="attrs[replaceid]">replacename</label><input class="form-control" placeholder="输入属性值" type="text" name="attrs[replaceid]"></div>';
  var groupDom = '<div class="col-sm-2"><label class="checkbox-inline"><input type="checkbox" name="groups[replaceid]" value="replacename"> replacename</label></div>';
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
  $("#add-attr-button").bind('click', function (e) {
    $(this).hide();
    $(this).next().show();
  })
  $("#add-group-button").bind('click', function (e) {
    $(this).hide();
    $(this).next().show();
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
  $('#add-group').bind('click', function () {
    var group = $("input[name='add-group-input']").val();
    group = $.trim(group);

    if (group == false) {
      alert('请输入正确的分组名');
      return;
    }
    if (group) {
      var url = '/goods/set-group';
      var data = {group: group};
      ajaxRequest(url, data, function (result) {
        if (result.code == 200) {
          for (var key in result.other) {
            replaceDom = groupDom.replace(/replaceid/g, key);
            replaceDom = replaceDom.replace(/replacename/g, result.other[key]);
            $(replaceDom).appendTo($("#append-group"));
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
function uploadFiles(url, files, that) {
    // console.log(that, that.dataset.img);return;
  var formData = new FormData();
  console.log(files);
  for (var i = 0, file; file = files[i]; ++i) {
    formData.append(i, file);
  }
  console.log(formData);
  $.ajax({
    url : url,
    type : 'POST',
    data : formData,
    async : false,
    processData : false,
    contentType : false,
    success : function (data) {
        console.log(data);
        // 上传成功
        if (data.code == 200) {
            // console.log(that, that.dataset.img);
            for (var i = 0; i < data.other.suc.length; i++) {
              $('<img src="'+data.other.suc[i]+'" class="img-rounded '+that.dataset.classname+'"/>').appendTo($(that).next());
              $('<input hidden type="text" name="'+that.dataset.inputname+'['+i+']" value = "'+data.other.suc[i]+'">').appendTo($(that).parent());
            }
            if (data.other.err.length >=1) {
              alert(data.other.err.jion(';'));
            }
            
        }
        // 上传错误
        if (data.code == 400) {
            alert(data.msg);
        }
        
    },
    error : function (data) {
        console.log(data);
        alert(data.msg);
    }
  })
  
}
// 绑定事件
var inputFiles = document.querySelectorAll('input[type="file"]');
for (var i = 0; i < inputFiles.length; i++) {
    inputFiles[i].addEventListener('change', function(e) {
      uploadFiles('/upload/upload', this.files, this);
  }, false);
}

$(':radio').click(function () {
  console.log($(this), $(this)[0].name, $(this).val());
  if ($(this)[0].name == 'describe') {
    if ($(this).val() == 1) {
      $('#descText').show();
      $('#descImgDiv').hide();
      // console.log($(this).next('div'));
    }else if($(this).val() == 2){
      $('#descText').hide();
      $('#descImgDiv').show();
    }else{
      $('#descText').hide();
      $('#descImgDiv').hide();
    }
  }
})
</script>

<?php $this->endBlock(); ?>