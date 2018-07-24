<?php
namespace backend\controllers;

use Yii;
use yii\web\Controller;
use common\models\CustomerModel;
use common\models\OrderModel;
/**
 * 商品控制器
 */
class TestController extends Controller
{
	public $enableCsrfValidation = false;
	public function actionIndex()
	{
		$num = CustomerModel::find()->where(['share_id' => '3'])->count();
		var_dump($num);exit;
		// echo json_encode(['code' => 200]);
	}
	public function actionTest()
	{
		// $sceneId = '1';
		// $openid = 'oCSMd0obtXDxrhvuExl0J14jzaSQ';
		// $unionid = 'oR2lB0RvBH3MXFXc2xgnXPpXhUhI';
		// (new CustomerModel)->Attention($sceneId, $openid, true, $unionid);
		(new OrderModel)->sendMessage('oGWDW5b1hzDHrkS0NGDF3Pbd5IwI', 10);
	}

	public function actionIp()
	{
		var_dump($_SERVER);
	}
}