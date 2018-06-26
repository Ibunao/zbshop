<?php
namespace api\controllers;

use Yii;

use api\controllers\bases\BaseController;
use common\helpers\HttpHelper;
use common\helpers\WxPay;
use common\models\TempModel;
use common\models\OrderModel;
/**
 * 订单类
 * Site controller
 */
class OrderController extends BaseController
{
	/**
	 * 创建订单
	 * @return [type] [description]
	 */
	public function actionCreate()
	{
		$data = json_decode(file_get_contents("php://input"), true);
		if (!($data && isset($data['openid']) && isset($data['data']))) {
			return $this->send(400, '参数错误');
		}

		$openid = $data['openid'];
		$data = $data['data'];
		// 检查数据创建订单
		$result = (new OrderModel)->setOrder($openid, $data);
		if ($result) {
			return $this->send(200, 'success', ['orderid' => $result, 'data' => $data]);
		}
		return $this->send(400, 'fail');
	}
	/**
	 * 微信支付
	 * @return [type] [description]
	 */
	public function actionPay()
	{
		$request = Yii::$app->request;
		$openid = $request->post('openid');
		$orderid = $request->post('orderid');
		$orderInfo = (new OrderModel)->getOrderInfo($orderid);
		$body = $orderInfo['order_id']; 
		$total_fee = $orderInfo['pay_price']*100;// 分钱
		if ($total_fee == 0) {
			$total_fee = 1;// 最少支付一分钱
		}
		$pay = new WxPay;
		$result = $pay->pay($openid, $orderid, $body, $total_fee);
		return $this->send(200, 'success', $result);
	}
	/**
	 * 微信支付异步通知
	 * @return [type]        [description]
	 */
	public function actionPayNotify()
	{
		// Yii::$app->cache->set('notify-in', true);
		$xml = isset($GLOBALS['HTTP_RAW_POST_DATA']) ? $GLOBALS['HTTP_RAW_POST_DATA'] : file_get_contents("php://input");
		// Yii::$app->cache->set('notify-here', true); 
  //     	Yii::$app->cache->set('notify', $xml);
	    //将服务器返回的XML数据转化为数组  
	    $payModel = new WxPay;
	    $data = $payModel->xmlToArray($xml);  
	    // 保存微信服务器返回的签名sign  
	    $data_sign = $data['sign'];  
	    // sign不参与签名算法  
	    unset($data['sign']); 
	    $sign = $payModel->getSign($data);  
	      
	    // 判断签名是否正确  判断支付状态  
	    if ( ($sign === $data_sign) && ($data['return_code'] == 'SUCCESS') && ($data['result_code'] == 'SUCCESS') ) {  
	        $result = $data;  
	        //获取服务器返回的数据  
	        $order_sn = $data['out_trade_no'];          //订单单号  
	        $openid = $data['openid'];                  //付款人openID  
	        $total_fee = $data['total_fee'];            //付款金额  
	        $transaction_id = $data['transaction_id'];  //微信支付流水号  
	          
	        //更新数据库  
	        (new OrderModel)->wxNotify($order_sn, $openid, $total_fee, $transaction_id);  
	          
	    }else{  
	        $result = false;  
	    }  
	    // Yii::$app->cache->set('notify-result', $result);
	    // 返回状态给微信服务器  
	    if ($result) {  
	        $str='<xml><return_code><![CDATA[SUCCESS]]></return_code><return_msg><![CDATA[OK]]></return_msg></xml>';  
	    }else{  
	        $str='<xml><return_code><![CDATA[FAIL]]></return_code><return_msg><![CDATA[签名失败]]></return_msg></xml>';  
	    }  
	    echo $str;  
	    // return $result;  
	}
	public function actionCondition()
	{
		$request = Yii::$app->request;
		$openid = $request->get('openid');
		$result = (new OrderModel)->orderCondition($openid);
		return $this->send(200, '', $result);
	}
	/**
	 * 待支付待发货等。待系列
	 * @return [type] [description]
	 */
	public function actionDaiinfo()
	{
		$request = Yii::$app->request;
		$type = $request->post('type');
		$openid = $request->post('openid');
		if($type == 'daifa'){
			$result = (new OrderModel)->daiInfo(['status' => 2, 'ship_status' => 0, 'openid' => $openid]);
		} elseif ($type == 'daifu') {
			$result = [];
		} elseif ($type == 'daishou') {
			$result = (new OrderModel)->daiInfo(['status' => 2, 'ship_status' => 1, 'openid' => $openid]);
		} elseif ($type == 'daiping') {
			$result = [];
		// 所有
		} else {
			$result = [];
		}	
		return $this->send(200, '', $result);
	}
}