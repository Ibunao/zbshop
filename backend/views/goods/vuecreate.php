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
  .x-disappear{
    float: left;
    margin:6px 10px;
  }
  .x-disappear-a{
    border:solid gray 1px;
    padding: 3px;
  }
  .x-disappear-b{
    border:solid gray 1px;
    padding: 3px;
    background-color: gray;
  }
</style>
<?php $this->endBlock(); ?>
<form action="/goods/create" method="post" onkeydown="if(event.keyCode==13){return false;}">
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
    <input type="radio" name="specification" id="inlineRadio2" value="1" @click = "getSpecs"> 多规格
  </label>
  
</div>
<div class="row" v-show="pickedSpec == 1">
  <div v-for="(items, name) in specs" class="col-sm-offset-2">
    <h5>{{name}} : <button type="button" :data-sid = "items[0]['sid']" :data-name = "name" @click="deleteSpecs" class="btn btn-danger">删除</button></h5> 
    <div class="checkbox-inline" class="checkbox" v-for="item in items">
      <label>
        <input type="checkbox" :id="'specsv'+item.svid" :value="item.name" @click="computeTable(name, item.name)"> {{item.name}}
      </label>
    </div>
  </div>
</div>
<div class="row" v-show="pickedSpec == 1">
  <div class="col-sm-offset-2">
     <button id="add-specs-button" type="button" class="btn btn-primary" data-toggle="modal" data-target="#add-specs-modal">添加/修改规格</button>
  </div>
</div>
<div class="row" v-show="specRequestEnd == 1">
  <table class="table table-bordered">
    <thead>
      <tr>
        <th>#</th>
        <th v-for="name in specTableHeader">{{name}}</th>
        <th>图片</th>
        <th>微信价</th>
        <th>原价(选填)</th>
        <th>库存</th>
        <th>商品条码</th>
      </tr>
    </thead>
    <tbody>
      <tr v-for="(item, index) in specTable" :class="index%2 == 0 ? 'active':'success'">
        <td>{{index+1}}</td>
        <td v-for="name in item">{{name}}</td>
        <td><input type="file" name=""></td>
        <td><input type="text" class="form-control" :name="goodsWxPrice" placeholder="销售价格"></td>
        <td><input type="text" class="form-control" name="goodsOriPrice" placeholder="原价格"></td>
        <td><input type="text" class="form-control" name="goodsStore" placeholder="库存"></td>
        <td><input type="text" class="form-control" name="goodsNo" placeholder="商品条码"></td>
      </tr>
    </tbody>
  </table>
</div>
<!-- 弹出框 -->
<div id="add-specs-modal" class="modal fade" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">添加/修改规格</h4>
      </div>
      <div class="modal-body">
        <label>添加规格名</label>
        <input id="input-add" v-model.trim="addSpecsAttr.name" class="form-control" placeholder="输入规格名" type="text" name="add-specs-input">
        <div class="checkbox">
          <label>
            <input type="checkbox" v-model="addSpecsAttr.main"> 勾选为主规格(如颜色等需要放不同展示图片的)
          </label>
        </div>
        <label>添加规格值</label>
        <div class="input-group">
          <input id="input-add" v-model.trim="addSpecsValue" class="form-control col-sm-8" placeholder="输入规格值" type="text" name="add-specs-input" aria-describedby="basic-addon2">
          <span class="input-group-addon" id="basic-addon2" @click="addSpecValue">添加</span>
        </div>
        <div class="row">
          <div class="x-disappear" v-for="item in addSpecsAttr.values">
            <span class="x-disappear-a">{{item}}</span><span :data-spec="item" class="x-disappear-b" @click="deleteSpec">&times;</span>
          </div>
        </div>
        
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">取消</button>
        <button type="button" class="btn btn-primary" @click = "addSpecs">保存</button>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
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
    specs:[],// 存放已有的规格
    addSpecsAttr:{// 添加的规格属性 周转
      name:"",// 规格名
      values:[], // 规格值
      main:false, //是否为主规格
    }, 
    addSpecsValue:"", // 添加的一个规格值。周转
    selectSpecs:{},
    specRequestEnd:0,// spec请求结束
    specTable:[], // table的渲染数据
    specTableHeader:[], // table的渲染数据
  },
  watch: {

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
    },
    addSpecs:function (event) {
      addSpecs();
    },
    addSpecValue:function (event) {
      if (this.addSpecsAttr.name) {
        var index = this.addSpecsAttr.values.indexOf(this.addSpecsValue);
        if (index != -1) {
          return;
        }
        this.addSpecsAttr.values.push(this.addSpecsValue);
        this.addSpecsValue = "";
      }else{
        alert("请添加规格名")
      }
    },
    deleteSpec:function (e) {
      console.log(e);
      var spec = e.target.dataset.spec;
      var index = this.addSpecsAttr.values.indexOf(spec);
      this.addSpecsAttr.values.splice(index, 1);
    },
    // 删除一个分类下的规格
    deleteSpecs:function (e) {
      var sid = e.target.dataset.sid;
      var name = e.target.dataset.name;
      deleteSpecs(sid, name);
    },
    // 计算table渲染数据
    computeTable:function (name, value) {
      console.log(name, value)
      var index = this.selectSpecs[name].value.indexOf(value);
      if (index == -1) {
        this.selectSpecs[name].value.push(value);
      }else{
        this.selectSpecs[name].value.splice(index, 1);
      }

      var data = [];
      this.specTableHeader = [];
      for (item in this.selectSpecs) {
        if (this.selectSpecs[item].value.length) {
          if (this.selectSpecs[item].main) {
            data.unshift(this.selectSpecs[item].value);
            this.specTableHeader.unshift(item);
          }else{
            data.push(this.selectSpecs[item].value);
            this.specTableHeader.push(item);
          }
        }
      }
      console.log(data);
      this.specTable = multiCartesian(data)
    }
  }
});
(function() {
    //笛卡尔积  
    var Cartesian = function(a, b) {
        var ret = [];
        for (var i = 0; i < a.length; i++) {
            for (var j = 0; j < b.length; j++) {
                ret.push(ft(a[i], b[j]));
            }
        }
        return ret;
    }
    var ft = function(a, b) {
        var ret = null;
        if (!(a instanceof Array)){
          ret = [a];
        }else{
          ret = a.slice();
        }
        
        // var ret = Array.call(null, a);
        ret.push(b);
        return ret;
    }
    //多个一起做笛卡尔积  
    multiCartesian = function(data) {
        var len = data.length;
        if (len == 0)
            return [];
        else if (len == 1)
            return data[0];
        else {
            var r = data[0];
            for (var i = 1; i < len; i++) {
                r = Cartesian(r, data[i]);
            }
            return r;
        }
    }
})();

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
    }else{
      app.attrs = {};
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
 * 获取一个分类下的规格/或所有
 * @return {[type]} [description]
 */
function getSpecs(name) {
  var cid = app.cid;

  if (!cid) {
    alert('请选择商品分类');
    app.pickedSpec = 0;
    return;
  }
  if (cid) {
    app.pickedSpec = 1;
    var url = '/goods/get-specs';
    var data = {cid: cid};
    if (name) {
      data.name = name;
    }
    ajaxRequest(url, data, function (result) {
      if (result.code == 200) {
        console.log(result.other);

        app.specs = result.other;
        for(index in app.specs){
          app.selectSpecs[index] = {value:[], main:app.specs[index][0]['main']};
        }
      }else{
        app.specs = {};
      }
      app.specRequestEnd = 1;
    });
  }
}
/**
 * 存储规格及规格值
 * @return {[type]} [description]
 */
function addSpecs() {
  var cid = app.cid;
  var spec = app.addSpecsAttr;
  if (!cid) {
    alert('请选择商品分类');
    app.pickedSpec = 0;
    return;
  }
  if (cid) {
    var url = '/goods/add-specs';
    var data = {cid: cid, spec: spec};
    ajaxRequest(url, data, function (result) {
      if (result.code == 200) {
        console.log(result.other);
        $("#add-specs-modal").modal("hide")
        app.addSpecsAttr = {};
      }else{
        alert(result.msg);
      }
    });
  }
}
function deleteSpecs(sid, name) {
  var cid = app.cid;

  var url = '/goods/delete-specs';
  var data = {cid: cid, sid: sid};
  ajaxRequest(url, data, function (result) {
    if (result.code == 200) {
      console.log(result.other);
      getSpecs();
    }else{
      alert(result.msg);
    }
  });
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