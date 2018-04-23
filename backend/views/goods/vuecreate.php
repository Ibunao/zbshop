<?php 
$this->title = '添加商品';
 ?>
<?php $this->beginBlock('endheader'); ?>
<script src="https://cdn.jsdelivr.net/npm/vue@2.5.16/dist/vue.js"></script>
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
     <select name="category" id="category" class="selectpicker show-tick form-control"  data-width="98%" data-first-option="true" title='请选择商品分类' required data-live-search="true" @change="selectChange" v-model="cid">
     	<!-- <option value="0">请选择商品分类</option> -->
     	<?php foreach (Yii::$app->params['categories'] as $key => $value): ?>
        	<option value="<?=$key ;?>"><?=str_repeat('--', $value['level'] - 1).$value['name'] ;?></option>
     	<?php endforeach ?>
     </select>
   </div>
  </div>
  <div id="attr-row" class="row">
   <label class="col-sm-2 control-label" for="#">商品属性(选填)</label>
   <div id="append-attr" class="row col-sm-10">
     <div class="col-sm-2 col-md-3 border-style form-inline" v-for="(value, key) in attrs">
      <label for="add-attr-input">{{value}}</label>
      <input id="input-add" class="form-control" placeholder="输入属性值" type="text" :name="'attrs['+key+']'">
     </div>
   </div>
   <div class="col-sm-offset-2">
     <button id="add-attr-button" type="button" class="btn btn-primary" data-toggle="modal" data-target="#add-attr-modal">添加新属性</button>
<!-- 弹出框 -->
<div id="add-attr-modal" class="modal fade" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">添加新属性</h4>
      </div>
      <div class="modal-body">
        <input id="input-add" v-model.trim="addAttrValue" class="form-control" placeholder="输入属性名" type="text" name="add-attr-input">
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">取消</button>
        <button type="button" class="btn btn-primary" @click = "addAttrs">保存</button>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
  </div>
</div>
<div class="row">
   <label class="col-sm-2 control-label" for="category">选择分组(选填)</label>
   <div id="append-group" class="row col-sm-10">
     <div class="col-sm-2" v-for="item in groups">
         <label class="checkbox-inline">
          <input type="checkbox" :name="'groups['+item.id+']'" :value="item.id"> {{item.name}}
        </label>
     </div>
   </div>
   <div class="col-sm-offset-2">
    <button id="add-group-button" type="button" class="btn btn-primary"  data-toggle="modal" data-target="#add-group-modal">添加新分组</button>
<!-- 弹出框 -->
<div id="add-group-modal" class="modal fade" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">添加新分组</h4>
      </div>
      <div class="modal-body">
        <input id="input-add" v-model.trim="addGroupValue" class="form-control" placeholder="输入分组名" type="text" name="add-group-input">
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">取消</button>
        <button type="button" class="btn btn-primary" @click = "addGroups">保存</button>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
   </div>
</div>
<div class="row form-inline">
  <label class="col-sm-2 control-label" for="category">商品名称</label>
  <input type="text" class="form-control" name="goodsName" placeholder="商品名称">
</div>
<div class="row form-inline">
  <label class="col-sm-2 control-label" for="category">商品规格</label>
  <label class="radio-inline">
    <input type="radio" name="specification" id="inlineRadio1" value="0" v-model="pickedSpec"> 单规格
  </label>
  <label class="radio-inline">
    <input type="radio" name="specification" id="inlineRadio2" value="1" v-model="pickedSpec" @click = "getSpecs"> 多规格
  </label>
</div>
<div v-show="pickedSpec == 0">
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
var app = new Vue({
  el: '#vueApp',// 根模板
  data: {
    message: 'Hello Vue!',
    cid:"",// 分类id
    attrs:{},// 属性
    attrModel:true,// 是否显示添加属性弹窗
    addAttrValue:"",//添加的属性值 周转
    groups:<?=json_encode($groups) ;?>,// 为了方便，没写接口
    addGroupValue:"",// 添加的分组值 周转
    pickedSpec:0, // 选择的规格
  },
  methods: {
    selectChange: function (event) {
      console.log(this.cid);
      if (this.cid) {
        getAttrs(this.cid);
      }
    },
    addAttrs:function (event) {
      addAttrs();
    },
    addGroups:function (event) {
      addGroups();
    },
    getSpecs:function (event) {
      getSpecs();
    }
  }
})
/**
 * 通过分类id获取属性
 * @param  {[type]} cid 分类id
 * @return {[type]}     [description]
 */
function getAttrs(cid) {
  var url = '/goods/get-attrs';
  var data = {cid : cid};
  ajaxRequest(url, data, function (res) {
    if (res.code == 200) {
      result = res.other;
      console.log(result);
      app.attrs = result;
    }
  });
}
/** 
 * 给分类添加属性
*/
function addAttrs() {
  var attr = app.addAttrValue;
  var cateid = app.cid;
  console.log(attr, cateid);
  if (!cateid) {
    alert('请先选择分类');
    return;
  }
  if (attr) {
    var url = '/goods/set-attr';
    var data = {attr: attr, cateid: cateid};
    ajaxRequest(url, data, function (result) {
      if (result.code == 200) {
        for (var key in result.other) {
          // 添加新值
          app.$set(app.attrs, key, result.other[key])
        }
        // 隐藏弹窗
        $("#add-attr-modal").modal("hide")
        app.addAttrValue = "";
      }else{
        alert(result.msg);
      }
    });
  }
}
/** 
 * 添加分组
*/
function addGroups() {
  var group = app.addGroupValue;

  if (!group) {
    alert('请输入正确的分组名');
    return;
  }
  if (group) {
    var url = '/goods/set-group';
    var data = {group: group};
    ajaxRequest(url, data, function (result) {
      if (result.code == 200) {
        console.log(result.other);
        app.groups.push(result.other)
        $("#add-group-modal").modal("hide")
        app.addGroupValue = "";
      }else{
        alert(result.msg);
      }
    });
  }
}
/**
 * 获取一个分类下的规格
 * @return {[type]} [description]
 */
function getSpecs() {
  var cid = app.cid;

  if (!cid) {
    alert('请选择商品分类');
    return;
  }
  if (cid) {
    var url = '/goods/get-specs';
    var data = {cid: cid};
    ajaxRequest(url, data, function (result) {
      if (result.code == 200) {
        console.log(result.other);
        app.groups.push(result.other)
        $("#add-specs-modal").modal("hide")
        app.addGroupValue = "";
      }else{
        alert(result.msg);
      }
    });
  }
}
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