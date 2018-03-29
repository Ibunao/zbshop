<?php
namespace wechat\controllers\bases;

use Yii;
use yii\web\Controller;


class BaseController extends Controller
{
	public $enableCsrfValidation = false;
	public function init()
	{
		// 展示页面不显示debug条
		Yii::$app->view->off(\yii\web\View::EVENT_END_BODY, [\yii\debug\Module::getInstance(), 'renderToolbar']);
	}
}