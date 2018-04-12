<?php
namespace api\controllers;

use Yii;

use yii\web\Controller;
use api\controllers\bases\BaseController;
use common\helpers\HttpHelper;

/**
 * Site controller
 */
class XcxController extends BaseController
{
	
	public function actionIndex($code)
	{
		$url = 'https://api.weixin.qq.com/sns/jscode2session?appid='.Yii::$app->params['wxconfig']['test']['app_id'].'&secret='.Yii::$app->params['wxconfig']['test']['app_secret'].'&js_code='.$code.'&grant_type=authorization_code';
		$result = HttpHelper::httpCurl($url);
		Yii::$app->cache->set('xcx-index', $result);
		return ['code' => 200, 'msg' => 'sucess'];
	}
	public function actionCache()
	{
		var_dump(Yii::$app->cache->get('xcx-index'));
	}
}