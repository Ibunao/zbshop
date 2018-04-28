<?php
namespace backend\controllers;

use Yii;
use yii\web\Controller;

/**
 * 商品控制器
 */
class TestController extends Controller
{
	public $enableCsrfValidation = false;
	public function actionTest()
	{
		return json_encode(['code' => 200, 'msg' => 'ok']);
	}
	public function actionIp()
	{
		// var_dump($_SERVER);
	}
}