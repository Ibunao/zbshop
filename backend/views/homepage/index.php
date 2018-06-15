<?php 
$this->title = '首页管理';
 ?>
 <script src="https://cdn.jsdelivr.net/npm/vue@2.5.16/dist/vue.js"></script>
<style>
  .success{
    padding: 10px;
    margin-top: 50px;
    background-color: #dff0d8;
    position: absolute;
    font-size: 30px;
  }
  .left-part{
    float: left;
    width:30%;
  }
  .right-part{
    float: left;
    width: 30%;
  }
  .right-button{
    float: right;
    width: 40%;
    position: relative;
    top: 0px;
    height: 0px;
  }
  .button-handle{
    width:100%;
    opacity: 0.6;
  }
  .popup-window{
    position: fixed;
    left: 250px;
    bottom: 300px;
    border: 2px solid #0382F9;
    width: 450px;
    height: 250px;
    background-color: #FFF;
    z-index: 100;
  }
  .popup-window-upload{
    margin: 10px 20px;
    width: 100px;
    margin-top: 50px;
  }
  .popup-window-submit{
    position: absolute;
    bottom: 10px;
    right: 10px;
  }
  .add-new{
    margin: 10px 2%;
    width: 96%;
    height: 160px;
    border: 1px solid red;
    position: relative;
  }
  .add-new-heng{
    display: inline-block;
    height: 0px;
    width: 100px;
    border: 2px solid rgba(128, 128, 128, 0.46);
    position: absolute;
    top: 50%;
    left: 50%;
    margin-left: -50px;
  }
  .add-new-shu{
    display: inline-block;
    height: 100px;
    width: 0px;
    border: 2px solid rgba(128, 128, 128, 0.46);
    position: absolute;
    top: 50%;
    left: 50%;
    margin-top: -50px;
  }
  .catelist{
  	width: 200px;
  	height: 80px;
  	display: inline-block;
  	background-color:red;
  	margin-left: 20px;
  	margin-bottom: 20px;
  	font-size: 30px;
  }
</style>
<div id="vue-app">
<div class="left-part">
  <!doctype html>
<html>
<head>
<title>首页管理</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimum-scale=1.0, maximum-scale=1.0">
<link href="https://m.octmami.com/themes/wap1_4/css/main.css?v=7" rel="stylesheet" type="text/css">
</head>
<body>
<!-- 弹窗 -->
<div v-show="isshow" class="popup-window">
  <!-- <img v-if='change.img' :src="change.img" width="10px"> -->
  <div v-if="change.type != 'catelist'" class="popup-window-upload">
  	<label for="updateImg">更新图片</label>
    <input type="file" name="updateImg" class="btn btn-info" @change="uploadImg($event)">
  </div>
  <div v-if="change.type == 'url'">
    <label for="updateUrl" style="font-weight:bold;">更新Url</label>
    <input type="text" name="updateUrl" class="form-control" v-model='change.url'>
  </div>
  <div v-if="change.type == 'groups'">
    <label for="updateGroups" style="font-weight:bold;">更新分组</label>
    <select class="form-control" name="updateGroups" v-model='change.gid'>
	  <option v-for='(item, index) in hasGroups' :value="item.id">{{item.name}}</option>
	</select>
    <label for="updateText" style="font-weight:bold;">更新名称</label>
    <input type="text" name="updateText" class="form-control" v-model='change.name'>
  </div>
  <div v-if="change.type == 'catelist'">
    <label for="updateGroups" style="font-weight:bold;">更新分组</label>
    <select class="form-control" name="updateGroups"  v-model='change.cid'>
	  <option v-for='(item, index) in hasCatelist' :value="item.id">{{item.name}}</option>
	</select>
    <label for="updateText" style="font-weight:bold;">更新名称</label>
    <input type="text" name="updateText" class="form-control" v-model='change.name'>
  </div>
  <div class="popup-window-submit">
    <label class="btn btn-info" @click='submitUpdate()'>提交</label>
  </div>
</div>
<!--正文-->
<div class="feature_new_set">
  <!--轮播-->
  <h3>轮播</h3>
  <div class="w100" v-for="(item, index) in title">
    <div class="right-button">
      <button type="button" class="btn btn-primary button-handle" data-type='title' :data-key='index' @click="update">更新</button>
    </div>
    <img :src="item.img">
  </div>
  <!--分组-->
  <h3>分组</h3>
  <div class="w640 clearfix">
    <div v-for="(item, index) in groups" class="flashSaleList lazyimg">
      <div class="right-button">
        <button type="button" class="btn btn-primary button-handle" data-type='groups' :data-key='index' @click="update">更新</button>
      </div>
      <img :src="item.img">
    </div>
  </div>
  <!--分类-->
  <h3>分类</h3>
  <div class="w640 clearfix">
    <div v-for="(item, index) in catelist" class="flashSaleList lazyimg">
      <div class="right-button">
        <button type="button" class="btn btn-primary button-handle" data-type='catelist' :data-key='index' @click="update">更新</button>
      </div>
      <span class="catelist">{{item.name}}</span>
    </div>
  </div>
  <!--推荐商品-->
  <!-- <h3>推荐商品</h3>
  <div class="w640 clearfix">
    <div v-for="(item, index) in products" class="flashSaleList lazyimg">
      <div class="right-button">
        <button type="button" class="btn btn-primary button-handle" data-type='products' :data-key='index' @click="update">更新</button>
      </div>
      <img :src="item.img">
    </div>
  </div> -->
</div>
<!--以上专题-->
</body>
</html>
</div>
<div class="right-part">
  <span @click='saveData' style="float: right;width: 50%;" class="btn btn-primary button-handle" >保存</span>
</div>
</div>
<script>
var host = 'http://admin.quutuu.com';
var vm = new Vue({
  el: '#vue-app',
  data: {
    isshow: false,
    change:{
      type:'',
      id:'',
      img:'',
      url:'',
      key:'',
      name:'',
      gid:'',
      gName:'',
      cid:'',
      cName:'',
    },
    // 默认title图片
    title:[],
    // [{
    //   id: '1',
    //   img: host+"/images/carousel/file_5966e5a206bec.png",
    //   url:'#',
    // },{
    //   id: '2',
    //   img: host+"/images/carousel/file_5966e5a206bec.png",
    //   url:'#',
    // }],
    // 分组
    groups:[],
    // [
    //   {
    //     id:1,
    //     img:host+'/images/groups/t_1500017601596873c12471a.png',
    //     url:'#',
    //     gid:'15',
    //     textContent:'军事'
    //   },
    //   {
    //     id:2,
    //     img:host+'/images/groups/file_59686b90612c4.jpg',
    //     url:'#',
    //     gid:'2',
    //     textContent:'野外'
    //   },
    //   {
    //     id:3,
    //     img:host+'/images/groups/file_59686b8c1f47d.jpg',
    //     url:'#',
    //     gid:'2',
    //     textContent:'高空'
    //   },
    //   {
    //     id:4,
    //     img:host+'/images/groups/t_150001571859686c66373ca.jpg',
    //     url:'#',
    //     gid:'2',
    //     textContent:'水中'
    //   }
    // ],
    // 分类
    catelist:[],
    // [
    //   {
    //     id:'1',
    //     name:'徒步露营',
    //     url:'#',
    //     cid:'1',
    //   },{
    //     id:'2',
    //     name:'测试1',
    //     url:'#',
    //     cid:'2',
    //   },{
    //     id:'3',
    //     name:'徒步露营',
    //     url:'#',
    //     cid:'3',
    //   },{
    //     id:'4',
    //     name:'徒步露营',
    //     url:'#',
    //     cid:'4',
    //   },
    // ],
    // products:[
    //   {
    //     id:'1',
    //     img:host+'/images/goods/1525860884209169.png',
    //     url:'#',
    //     goodsId:'1',
    //     name:'2017新款冲锋衣三合一两件套男女情侣款户外服装',
    //     about:'分类：冲锋', 
    //     price: 110,
    //   },{
    //     id:'2',
    //     img:host+'/images/goods/1525860884209169.png',
    //     url:'#',
    //     goodsId:'1',
    //     name:'2017新款冲锋衣三合一两件套男女情侣款户外服装',
    //     about:'分类：冲锋', 
    //     price: 110,
    //   },{
    //     id:'3',
    //     img:host+'/images/goods/1525860884209169.png',
    //     url:'#',
    //     goodsId:'1',
    //     name:'2017新款冲锋衣三合一两件套男女情侣款户外服装',
    //     about:'分类：冲锋', 
    //     price: 110,
    //   },
    //   {
    //     id:'4',
    //     img:host+'/images/goods/1525860884209169.png',
    //     url:'#',
    //     goodsId:'1',
    //     name:'2017新款冲锋衣三合一两件套男女情侣款户外服装',
    //     about:'分类：冲锋', 
    //     price: 110,
    //   },
    // ],
    hasGroups:<?=json_encode($groups) ;?>,
    hasCatelist:<?=json_encode($catelist) ;?>
  },
  // 周期函数
  created:function () {
    var data = <?=json_encode($data) ;?>;
    // console.log(data);
    if (data['groups']) {
      this.groups = data['groups'];
      console.log(this.groups);
    }
    if (data['title']) {
      this.title = data['title'];
    }
    if (data['catelist']) {
      this.catelist = data['catelist'];
    }
  },
  methods:{

    // 更新
    update: function (e) {
      var type = e.target.dataset.type;
      var key = e.target.dataset.key;
      console.log(type, key, this[type][key])
      this.isshow = true;
      this.change.type = type;
      this.change.key = key;
      this.change.id = this[type][key].id;
      this.change.url = '#';
      if (type !== 'catelist') {
      	this.change.img = this[type][key].img;
      	this.change.name = '';
      }
      if (type == 'catelist') {
      	this.change.name = this[type][key].name;
      	this.change.cid = this[type][key].cid;
      }
      if (type == 'groups') {
      	this.change.gid = this[type][key].gid;
      	this.change.name = this[type][key].textContent;
      }
    },
    // 修改数据
    submitUpdate: function () {
    	if (this.change.type == 'title') {
    		this.title[this.change.key].img = this.change.img;
    	}
    	if (this.change.type == 'groups') {
    		this.groups[this.change.key].img = this.change.img;
    		this.groups[this.change.key].gid = this.change.gid;
    		this.groups[this.change.key].textContent = this.change.name;
    	}
    	if (this.change.type == 'catelist') {
    		this.catelist[this.change.key].cid = this.change.cid;
    		this.catelist[this.change.key].name = this.change.name;
    	}
    	this.isshow = false;
      // $.post('/homepage/save', this.data, function (res) {
      //   // console.log(res,res.msg);return;
        
      //   alert(res.msg);
      //   if ("other" in res) {
      //     if (vm.change.key) {
      //       vm.$set(vm[vm.change.type], vm.change.key, res.other);
      //     }else{
      //       vm[vm.change.type].push(res.other)
      //     }
      //     vm.isshow = false;
      //   }
      // },'json'
      // );
    },
    uploadImg:function (event) {
      uploadFiles('/upload/upload', event.target.files, function (data) {
        console.log(data);
        // 上传成功
        if (data.code == 200) {
            // console.log(that, that.dataset.img);
            vm.change['img'] = host+data.other.suc[0]
            // vm.change['img'] = data.other.suc[0]
            
        }
        // 上传错误
        if (data.code == 400) {
            alert(data.msg);
        }

      });
    },
    saveData:function () {
    	var url = '/homepage/save-data'
    	$.ajax({
	    url : url,
	    type : 'POST',
	    data : vm.$data,
	    success : function (data) {
	    	
	    },
	    error : function (data) {
	        console.log(data);
	    }
	  })
    }
  }
}) 
function uploadFiles(url, files, suc) {
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
    success : suc,
    error : function (data) {
        console.log(data);
    }
  })
  
}
</script>