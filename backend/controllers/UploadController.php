<?php
namespace backend\controllers;

use Yii;
use backend\controllers\bases\BaseController;

/**
 * 商品控制器
 */
class UploadController extends BaseController
{
	/**
     * 上传图片
     * @return [type] [description]
     */
    public function actionUpload()
    {
        Yii::$app->response->format = 'json';
        $error = [];
        $success = [];
        // 允许的格式
        $typeArr = ['image/jpeg', 'image/png', 'image/jpg'];
        foreach ($_FILES as $key => $value) {
        	if(!in_array($value['type'], $typeArr)) { 
	            $error[] = $value['name'].'格式错误';
	            continue;
	        } 
	        if(is_uploaded_file($value['tmp_name'])) {  
	            $uploadedFile = $value['tmp_name'];  
	      
	            $path = Yii::$app->params['goodsImagesPath']; 
	            //判断该用户文件夹是否已经有这个文件夹  
	            if(!file_exists($path)) {  
	                mkdir($path, 0777, true);  
	            }  
	      
	            $fileTrueName=$value['name']; 
	            $img = time().rand(1,1000).rand(1,1000).substr($fileTrueName, strrpos($fileTrueName,"."));
	            $moveToFile = $path.$img;  
	            if(move_uploaded_file($uploadedFile, iconv("utf-8","gb2312",$moveToFile))) {
	            	$success[] = Yii::$app->params['goodsImagesUrl'].$img;
	            } else { 
	            	$error[] = $value['name'].'上传失败';
	            }  
	        }
        }
        if (empty($success)) {
        	return $this->send(400, '全部上传失败');
        }
        return $this->send(200, '成功或部分成功', ['err' => $error, 'suc' => $success]);
    }
}