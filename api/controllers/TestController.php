<?php
namespace api\controllers;

use Yii;

use yii\web\Controller;
use api\controllers\bases\BaseController;


/**
 * Site controller
 */
class TestController extends BaseController
{
	
	public function actionIndex()
	{
		return ['code' => 200, 'msg' => 'sucess'];
	}
}