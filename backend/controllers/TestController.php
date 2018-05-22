<?php
namespace backend\controllers;

use Yii;
use yii\web\Controller;
use common\models\CustomerModel;
/**
 * 商品控制器
 */
class TestController extends Controller
{
	public $enableCsrfValidation = false;
	public function actionTest()
	{
		$sceneId = '1';
		$openid = 'oCSMd0obtXDxrhvuExl0J14jzaSQ';
		$unionid = 'oR2lB0RvBH3MXFXc2xgnXPpXhUhI';
		(new CustomerModel)->Attention($sceneId, $openid, true, $unionid);
	}
	public function actionIp()
	{
		// var_dump($_SERVER);
	}
}