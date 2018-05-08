<?php
namespace common\helpers;

use Yii;
/**
* 小程序微信支付
*/
class WxPay
{
	// 小程序id
	protected $appid;  
	// 商户号
    protected $mch_id;  
    // 商户密码
    protected $key;  
    // 用户的openid
    protected $openid; 
    // 订单号 
    protected $out_trade_no;
    // 商品描述  
    protected $body;  
    // 总金额
    protected $total_fee;  
    function __construct() {
    	$params = Yii::$app->params; 
    	$this->mch_id = $params['wxconfig']['prod']['mch_id'];  
        $this->key = $params['wxconfig']['prod']['mch_key']; 
        $this->appid = $params['wxconfig']['prod']['app_id'];
    }  
    /**
     * 统一下单 支付入口
     * @return [type] [description]
     */
    public function pay($openid, $out_trade_no, $body, $total_fee) {  
        $this->openid = $openid;  
        $this->out_trade_no = $out_trade_no;  
        $this->body = $body;  
        $this->total_fee = $total_fee;  

        $return = $this->weixinapp();  
        return $return;  
    }  
  	//微信小程序接口  
    private function weixinapp() {  
        //统一下单接口  
        $unifiedorder = $this->unifiedorder();  
       var_dump($unifiedorder);exit;
        $params = array(  
            'appId' => $this->appid, //小程序ID  
            'timeStamp' => '' . time() . '', //时间戳  
            'nonceStr' => $this->createNoncestr(), //随机串  
            'package' => 'prepay_id=' . $unifiedorder['prepay_id'], //数据包  
            'signType' => 'MD5'//签名方式  
        );  
        //签名  
        $params['paySign'] = $this->getSign($params);  
        return $params;  
    }  
  
    /**
     * 调用微信统一下单接口
     * @return [type] [description]
     */
    private function unifiedorder() {  
        $url = 'https://api.mch.weixin.qq.com/pay/unifiedorder';  
        $params = [
            'appid' => $this->appid, //小程序ID  
            'mch_id' => $this->mch_id, //商户号  
            'nonce_str' => $this->createNoncestr(), //随机字符串  
            'body' => $this->body,  // 商品描述  
            'out_trade_no'=> $this->out_trade_no,  // 订单号
            'total_fee' => $this->total_fee,  //总金额 单位 分  
            'spbill_create_ip' => $_SERVER['REMOTE_ADDR'], //终端IP  
            'notify_url' => 'https://api.quutuu.com/order/pay-notify', //通知地址  确保外网能正常访问  
            'openid' => $this->openid, //用户id  
            'trade_type' => 'JSAPI'//交易类型  
        ];  
        // 计算签名  
        $params['sign'] = $this->getSign($params);
        // var_dump($params);  
        $xmlData = $this->arrayToXml($params);  
        var_dump($xmlData);//exit;
        $return = $this->xmlToArray($this->postXmlCurl($xmlData, $url, 60));  
        return $return;  
    }  
  
  	/**
  	 * 发送请求
  	 * @param  string  $xml    数据
  	 * @param  [type]  $url    [description]
  	 * @param  integer $second [description]
  	 * @return [type]          [description]
  	 */
    private static function postXmlCurl($xml, $url, $second = 30)   
    {  
        $ch = curl_init();  
        //设置超时  
        curl_setopt($ch, CURLOPT_TIMEOUT, $second);  
        curl_setopt($ch, CURLOPT_URL, $url);  
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);  
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE); //严格校验  
        //设置header  
        curl_setopt($ch, CURLOPT_HEADER, FALSE);  
        //要求结果为字符串且输出到屏幕上  
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);  
        //post提交方式  
        curl_setopt($ch, CURLOPT_POST, TRUE);  
        curl_setopt($ch, CURLOPT_POSTFIELDS, $xml);  
  
  
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 20);  
        curl_setopt($ch, CURLOPT_TIMEOUT, 40);  
        set_time_limit(0);  
  
  
        //运行curl  
        $data = curl_exec($ch);  
        //返回结果  
        if ($data) {  
            curl_close($ch);  
            return $data;  
        } else {  
            $error = curl_errno($ch);  
            curl_close($ch);  
            throw new WxPayException("curl出错，错误码:$error");  
        }  
    }  
      
      
      
    /**
     * 数组转换成xml
     * @param  [type] $arr [description]
     * @return [type]      [description]
     */
    private function arrayToXml($arr) {  
        ksort($arr);
        $xml = "<xml>";  
        foreach ($arr as $key => $val) {  
            if (is_array($val)) {  
                $xml .= "<" . $key . "><![CDATA[" . $this->arrayToXml($val) . "]]></" . $key . ">";  
            } else {  
                $xml .= "<" . $key . "><![CDATA[" . $val . "]]></" . $key . ">";  
            }  
        }  
        $xml .= "</xml>";  
        return $xml;  
    }  
  
  
    /**
     * xml转换成数组
     * @param  [type] $xml xml数据
     * @return [type]      [description]
     */
    private function xmlToArray($xml) {  
  
  
        //禁止引用外部xml实体   
        libxml_disable_entity_loader(true);  
        $xmlstring = simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_NOCDATA);  
        $val = json_decode(json_encode($xmlstring), true);  
        return $val;  
    }  
  
    /**
     * 产生随机字符串
     * @param  integer $length [description]
     * @return [type]          [description]
     */
    private function createNoncestr($length = 32) {  
        $chars = "abcdefghijklmnopqrstuvwxyz0123456789";  
        $str = "";  
        for ($i = 0; $i < $length; $i++) {  
            $str .= substr($chars, mt_rand(0, strlen($chars) - 1), 1);  
        }  
        return $str;  
    }  
  
  
    /**
     * 生成签名
     * @param  Array $arr 生成签名的参数
     * @return [type]      [description]
     */
    private function getSign($arr) {  
        foreach ($arr as $k => $v) {  
            $params[$k] = $v;  
        }  
        //签名步骤一：按字典序排序参数  
        ksort($params);  
        $str = $this->formatBizQueryParaMap($params, false);  
        //签名步骤二：在str后加入KEY  
        $str = $str . "&key=" . $this->key;  
        //签名步骤三：MD5加密  
        $str = md5($str);  
        //签名步骤四：所有字符转为大写  
        $result = strtoupper($str);  
        return $result;  
    }  
  
  
    ///作用：格式化参数，签名过程需要使用 
    /**
     * 供签名使用 
     * @param  [type] $paraMap   参数
     * @param  [type] $urlencode 是否urlencode转码
     * @return [type]            [description]
     */
    private function formatBizQueryParaMap($params, $urlencode) {  
        $buff = "";  
        ksort($params);  
        foreach ($params as $k => $v) {  
            if ($urlencode) {  
                $v = urlencode($v);  
            }  
            $buff .= $k . "=" . $v . "&";  
        }  
        $reqPar = '';  
        if (strlen($buff) > 0) {  
            $reqPar = substr($buff, 0, strlen($buff) - 1);  
        }  
        return $reqPar;  
    }  
  
}