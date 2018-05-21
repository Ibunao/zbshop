<?php
namespace api\controllers;

use Yii;
use api\controllers\bases\BaseController;
use common\helpers\HttpHelper;
use common\models\CustomerModel;
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
			// 测试
			Yii::$app->cache->set('xcx-test-userinfo', ['openid' => $openid, 'unionid' => $unionid]);
			// 根据unionid来绑定公众号openid和小程序的openid
			(new CustomerModel)->xcxAttention($openid, $unionid);
			// 返回openid
			return ['code' => 200, 'openid' => $result['openid']];
		}
		var_dump($result);exit;
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
	public function actionGetHomePage()
	{
		$host = 'http://admin.shop.com';
		// 轮播
		$result['carousel'] = [
			['pic' => $host.'/images/carousel/file_5966e5a206bec.png'],
			['pic' => $host.'/images/carousel/file_5966e5a206bec.png']
		];
		// 分组
		$result['groups'] = [
			['content' => $host.'/images/groups/t_1500017601596873c12471a.png', 'textContent' => '军事', 'group' => 1], 
			['content' => $host.'/images/groups/file_59686b90612c4.jpg', 'textContent' => '野外', 'group' => 2],
			['content' => $host.'/images/groups/file_59686b8c1f47d.jpg', 'textContent' => '高空', 'group' => 3],
			['content' => $host.'/images/groups/t_150001571859686c66373ca.jpg', 'textContent' => '水中', 'group' => 4]];
		// 分类
		$result['classify'] = [
			['name' => '徒步露营', 'cid' => 1],
			['name' => '徒步露营', 'cid' => 1],
			['name' => '徒步露营', 'cid' => 1],
			['name' => '徒步露营', 'cid' => 1],
		];
		// 商品。
		$result['goods'] = [
			['image' => 'http://admin.quutuu.com/images/goods/1525860884209169.png','goodsId' => 28, 'name' => "2017新款冲锋衣三合一两件套男女情侣款户外服装", 'about' => '分类：冲锋', 'price' => 110],
			['image' => 'http://admin.quutuu.com/images/goods/1525860884209169.png','goodsId' => 28, 'name' => "2017新款冲锋衣三合一两件套男女情侣款户外服装", 'about' => '分类：冲锋', 'price' => 110],
			['image' => 'http://admin.quutuu.com/images/goods/1525860884209169.png','goodsId' => 28, 'name' => "2017新款冲锋衣三合一两件套男女情侣款户外服装", 'about' => '分类：冲锋', 'price' => 110],
			['image' => 'http://admin.quutuu.com/images/goods/1525860884209169.png','goodsId' => 28, 'name' => "2017新款冲锋衣三合一两件套男女情侣款户外服装", 'about' => '分类：冲锋', 'price' => 110],
		];
		return $this->send(200, '', $result);
	}
	public function actionCache()
	{
		// var_dump(Yii::$app->cache->get('xcx-index'));
		var_dump(Yii::$app->cache->get('xcx-test-userinfo'));
	}
}