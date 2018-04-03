<?php
namespace backend\controllers;

use Yii;
use backend\controllers\bases\BaseController;

/**
 * 商品控制器
 */
class GoodsController extends BaseController
{

	public function actionIndex()
	{
		
		return $this->render('index');
	}
}