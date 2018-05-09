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
	/**
	 * 小程序login接口获取session_key和、openid和unionid
	 * @param  [type] $code [description]
	 * @return [type]       [description]
	 */
	public function actionIndex($code)
	{
		$url = 'https://api.weixin.qq.com/sns/jscode2session?appid='.Yii::$app->params['wxconfig']['prod']['app_id'].'&secret='.Yii::$app->params['wxconfig']['prod']['app_secret'].'&js_code='.$code.'&grant_type=authorization_code';
		// var_dump($url);exit;
		$result = HttpHelper::httpCurl($url);
		// 如果请求成功，存储openid和unionid的逻辑
		if (isset($result['openid'])) {
			$unionid = isset($result['unionid'])?$result['unionid']:'';
			$openid = $result['openid'];
			$session_key = $result['session_key'];

			// 返回openid
			return ['code' => 200, 'openid' => $result['openid']];
		}
		
		return ['code' => 400, 'msg' => '获取openid失败'];;
	}
	/**
	 * 保存用户信息
	 * @return [type] [description]
	 */
	public function actionSaveUserInfo()
	{
		$request = Yii::$app->request;
		$nickName = $request->post('nickName');
		$avatarUrl = $request->post('avatarUrl');
		$city = $request->post('city');
		$country = $request->post('country');
		$gender = $request->post('gender');
		$province = $request->post('province');
		$openid = $request->post('openid');
		$unionid = $request->post('unionid');
		// 存储
		
		// var_dump(json_decode($userInfo), $_POST, file_get_contents("php://input"));exit;
		return $this->send(200, "添加成功", $_POST);
	}
	public function actionGetIntegral()
	{
		return $this->send(200, '', ['integral' => 666]);
	}
	public function actionCache()
	{
		var_dump(Yii::$app->cache->get('xcx-index'));
	}
}