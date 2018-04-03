<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \frontend\models\SignupForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = '注册';
$this->params['breadcrumbs'] = [];
?>
<?php $this->beginBlock('endheader'); ?>
<style type="text/css">
    #imga{
        position: relative;
    }
    #imgb{
        position: relative;
    }
    .imgfloat{
        position: absolute;
        left: 0px;
        top: 0px;
    }
</style>
<?php $this->endBlock(); ?>
<div class="site-signup">
    <!-- <h1><?= Html::encode($this->title) ?></h1> -->

    <p>填写下面注册信息:</p>

    <div class="row">
        <div class="col-lg-5">
            <?php $form = ActiveForm::begin(['id' => 'form-signup']); ?>

                <?= $form->field($model, 'username')->textInput(['autofocus' => true]) ?>
                <?= $form->field($model, 'mobile')->textInput() ?>
                <?= $form->field($model, 'sex')->dropDownList(
                                [ 
                                    0 => '女',
                                    1 => '男'
                                ], 
                                ['prompt'=>'请选择性别']); ?>
                <?= $form->field($model, 'age')->textInput() ?>
                <?= $form->field($model, 'idcard_img1')->textInput()->hiddenInput()->label(false) ?>
                <?= $form->field($model, 'idcard_imga')->fileInput(['data-img' => 'imga']) ?>
                <div id="imga" hidden="hidden">
                    <img style="width: 100%" src="">
                    <img style="width: 100%" class="imgfloat" src="/images/helpers/shuiyin.png">
                </div>
                <?= $form->field($model, 'idcard_img2')->textInput()->hiddenInput()->label(false) ?>
                <?= $form->field($model, 'idcard_imgb')->fileInput(['data-img' => 'imgb']) ?>
                <div id="imgb" hidden="hidden">
                    <img style="width: 100%" src="">
                    <img style="width: 100%" class="imgfloat" src="/images/helpers/shuiyin.png">
                </div>
                <?= $form->field($model, 'bankcard')->textInput() ?>
                <?= $form->field($model, 'bank')->textInput() ?>
                <?= $form->field($model, 'area')->textInput() ?>
                <?= $form->field($model, 'password')->passwordInput() ?>
                <?= $form->field($model, 'password2')->passwordInput() ?>

                <div class="form-group">
                    <?= Html::submitButton('提交', ['class' => 'btn btn-primary', 'name' => 'signup-button']) ?>
                </div>

            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>
<?php $this->beginBlock('endbody'); ?>
<script>
function uploadFiles(url, files, that) {
    // console.log(that, that.dataset.img);return;
  var formData = new FormData();
  for (var i = 0, file; file = files[i]; ++i) {
    formData.append('abc', file);
  }
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
            imgId = that.dataset.img;
            $('#'+imgId).show();
            $('#'+imgId + ' img')[0].src = data.url;
            if (imgId == 'imga') {
                $("input[name='AgentUserModel[idcard_img1]']").val(data.url);
            }else{
                $("input[name='AgentUserModel[idcard_img2]']").val(data.url);
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
  uploadFiles('/site/upload', this.files, this);
}, false);
}
// document.querySelector('input[type="file"]').addEventListener('change', function(e) {
//   uploadFiles('/site/upload', this.files, this);
// }, false);
</script>
<?php $this->endBlock(); ?>
