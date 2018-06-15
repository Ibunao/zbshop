<?php
namespace api\controllers;

use Yii;
use api\controllers\bases\BaseController;
use common\helpers\HttpHelper;
use common\models\CustomerModel;
use common\helpers\xcx\WXBizDataCrypt;
use common\models\HomepageModel;
use common\models\GoodsModel;
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
			Yii::$app->cache->set('xcx-userinfo-'.$openid, ['openid' => $openid, 'unionid' => $unionid, 'session_key' => $result['session_key']]);
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
		$userInfo = $request->post('userInfo');
		$rawData = $request->post('rawData');
		$signature = $request->post('signature');
		$encryptedData = $request->post('encryptedData');
		$iv = $request->post('iv');
		$openid = $request->post('openid');
		// 获取unionid  
		$data = Yii::$app->cache->get('xcx-userinfo-'.$openid);
		if (!$data) {
			return $this->send(400, "数据错误", $_POST);
		}
		// 存储
		if(!$data['unionid']){
			$pc = new WXBizDataCrypt(Yii::$app->params['wxconfig']['prod']['app_id'], $data['session_key']);
			$errCode = $pc->decryptData($encryptedData, $iv, $decodeDatas);
			if(isset($decodeDatas['unionid'])){
				// 根据unionid来绑定公众号openid和小程序的openid
				(new CustomerModel)->xcxAttention($openid, $decodeDatas['unionid']);
			}
			return $this->send(200, "添加成功", ['code' => $errCode, 'data' => $decodeDatas]);
		}
		// var_dump(json_decode($userInfo), $_POST, file_get_contents("php://input"));exit;
		return $this->send(200, "添加成功", $_POST);
	}
	/**
	 * 获取积分
	 * @return [type] [description]
	 */
	public function actionGetIntegral($openid)
	{
		$result = (new CustomerModel)->getIntegral($openid);
		return $this->send(200, '', ['integral' => $result]);
	}
	/**
	 * 首页的数据
	 * @return [type] [description]
	 */
	public function actionGetHomePage()
	{
		$host = 'http://admin.quutuu.com';
		$model = HomepageModel::findOne(1);
		$result = [];
		if ($model) {
			$temp = json_decode($model->data, true);
			$result['carousel'] = $temp['carousel'];
			$result['groups'] = $temp['groups'];
			$result['classify'] = $temp['classify'];

			$goods = GoodsModel::find()
				->select(['id goodsId', 'wx_price', 'image', 'name'])
				->orderBy([
				    'id' => SORT_DESC,
				    'created_at' => SORT_DESC,
				])->limit(4)
				->asArray()
				->all();
			foreach ($goods as $key => $item) {
				$result['goods'][] = ['image' => $host.$item['image'], 'goodsId' => $item['goodsId'], 'name' => $item['name'], 'about' => '热卖推荐', 'price' => $item['wx_price'], 'id' => $item['goodsId']];
			}
		// 默认的
		}else{
			// 轮播
			$result['carousel'] = [
				['pic' => $host.'/images/carousel/file_5966e5a206bec.png', 'id' => 1],
				['pic' => $host.'/images/carousel/file_5966e5a206bec.png', 'id' => 2]
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
			// $result['goods'] = [
			// 	['image' => 'http://admin.quutuu.com/images/goods/1525860884209169.png','goodsId' => 28, 'name' => "2017新款冲锋衣三合一两件套男女情侣款户外服装", 'about' => '分类：冲锋', 'price' => 110, 'id'=>36],
			// 	['image' => 'http://admin.quutuu.com/images/goods/1525860884209169.png','goodsId' => 28, 'name' => "2017新款冲锋衣三合一两件套男女情侣款户外服装", 'about' => '分类：冲锋', 'price' => 110, 'id'=>36],
			// 	['image' => 'http://admin.quutuu.com/images/goods/1525860884209169.png','goodsId' => 28, 'name' => "2017新款冲锋衣三合一两件套男女情侣款户外服装", 'about' => '分类：冲锋', 'price' => 110, 'id'=>36],
			// 	['image' => 'http://admin.quutuu.com/images/goods/1525860884209169.png','goodsId' => 28, 'name' => "2017新款冲锋衣三合一两件套男女情侣款户外服装", 'about' => '分类：冲锋', 'price' => 110, 'id'=>36],
			// ];
		}
		
		return $this->send(200, '', $result);
	}
	public function actionCache()
	{
		// var_dump(Yii::$app->cache->get('xcx-index'));
		var_dump(Yii::$app->cache->get('xcx-test-userinfo'));
	}
}