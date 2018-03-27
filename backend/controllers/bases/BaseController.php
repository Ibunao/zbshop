<?php
namespace backend\controllers\bases;

use Yii;
use yii\web\Controller;

/**
 * 基础类
 */
class BaseController extends Controller
{
	public function init()
	{
		parent::init();
		if (Yii::$app->user->getIsGuest()) {
			$this->redirect('/site/login');
		}
	}
}