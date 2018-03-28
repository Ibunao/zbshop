<?php
namespace wechat\helpers;
use Yii;
use yii\base\Object;
use wechat\helpers\HttpHelper;
use yii\web\Cookie;
/**
 * 微信辅助
 */
class WchatHelper extends Object
{
    public $wxconfig;
    public $postObj;
    public $fromUsername;
    public $toUsername;
    public $time;
    public $keyword;
    public $latitude;
    public $longitude;
    public function init()
    {
        $this->wxconfig = Yii::$app->params['wxconfig'];
    }

    //实现valid验证方法：实现对接微信公众平台
    public function valid()
    {
        //接收随机字符串
        $echoStr = $_GET["echostr"];
        $token = $this->wxconfig['zbshop']['voidtoken'];
        //valid signature , option
        //进行用户数字签名验证
        if($this->checkSignature($token)){
            //如果成功，则返回接收到的随机字符串
            echo $echoStr;
            //退出
            exit;
        }
    }

    //定义自动回复功能
    public function responseMsg()
    {
        //get post data, May be due to the different environments
        //接收用户端发送过来的XML数据
        $postStr = $GLOBALS["HTTP_RAW_POST_DATA"];

        // Yii::app()->cache->set('mendian', $postStr);
        //extract post data
        //判断XML数据是否为空
        if (!empty($postStr)){
            /* libxml_disable_entity_loader is to prevent XML eXternal Entity Injection,
               the best way is to check the validity of xml by yourself */
            libxml_disable_entity_loader(true);
            //通过simplexml进行xml解析
            $this->postObj = $postObj = simplexml_load_string($postStr, 'SimpleXMLElement', LIBXML_NOCDATA);
            //手机端  发送方帐号（一个OpenID） 
            $this->fromUsername = $postObj->FromUserName;
            // 获取openid
            // Yii::$app->cache->set('openid', (string)$this->fromUsername);
            //开发者微信号（公共号）
            $this->toUsername = $postObj->ToUserName;
            //接收用户发送的关键词
            $this->keyword = trim($postObj->Content);

            //时间戳
            $this->time = time();
            //接收用户消息类型
            $msgType = $postObj->MsgType;
            
            switch ($msgType) {
                //关注或取消关注是触发的事件消息类型
                case 'event':
                    $this->event();
                    break;
                //用户发送的文本信息类型
                // case 'text':
                //     // $this->text();
                //     break;
                default:
                    # code...
                    break;
            }
        }else {
            echo "";
            exit;
        }
    }
    /**
     * 微信事件
     * @return [type] [description]
     */
    private function event()
    {
        $event = strtolower($this->postObj->Event);
        switch ($event) {
            case 'subscribe'://关注的时候
                $this->sendText($this->postObj->EventKey);
                // $this->sendImageText();
                break;
            // 已关注用户扫描带场景的二维码
            // case 'scan':
            //     $this->sendText('你扫码的scene_id是' . $this->postObj->EventKey);
            //     break;
            // 模板消息返回数据
            // case 'templatesendjobfinish':
            //     $arr = [
            //         // 创建时间
            //         'createtime' => (string)$this->postObj->CreateTime,
            //         // 消息id
            //         'msgid' => (string)$this->postObj->MsgID,
            //         // 状态
            //         'status' => (string)$this->postObj->Status,
            //         // 用户id
            //         'userid' => (string)$this->postObj->ToUserName,
            //     ];
            //     Yii::$app->cache->set('template', $arr);
                break;
            default:
                # code...
                break;
        }
    }
    /**
     * 回复符文消息，目前是写死的
     */
    private function sendImageText()
    {
        //图文发送模板
        $newsTpl = "<xml>
                    <ToUserName><![CDATA[%s]]></ToUserName>
                    <FromUserName><![CDATA[%s]]></FromUserName>
                    <CreateTime>%s</CreateTime>
                    <MsgType><![CDATA[%s]]></MsgType>
                    <ArticleCount>%s</ArticleCount>
                    %s
                    </xml>";
        //定义回复类型
        $msgType='news';
        //定义返回图文数量
        $count = 1;
        //组装Articles节点信息
        $str = '<Articles>';
        for($i=1;$i<=$count;$i++) {
            $str .= "<item>
                    <Title><![CDATA[ibunao{$i}]]></Title> 
                    <Description><![CDATA[趣途文化]]></Description>
                    <PicUrl><![CDATA[http://czbk888.duapp.com/images/{$i}.jpg]]></PicUrl>
                    <Url><![CDATA[http://www.quutuu.com]]></Url>
                    </item>";
        }
        $str .= '</Articles>';
        //格式化字符串
        $resultStr = sprintf($newsTpl, $this->fromUsername, $this->toUsername, $this->time, $msgType, $count, $str);
        //输出XML数据并返回到微信客户端
        echo $resultStr;
    }
    /**
     * 发送文本消息
     * @param  string $contentStr 要发送的内容
     */
    private function sendText($contentStr)
    {
        //文本发送模板
        $textTpl = "<xml>
                    <ToUserName><![CDATA[%s]]></ToUserName>
                    <FromUserName><![CDATA[%s]]></FromUserName>
                    <CreateTime>%s</CreateTime>
                    <MsgType><![CDATA[%s]]></MsgType>
                    <Content><![CDATA[%s]]></Content>
                    <FuncFlag>0</FuncFlag>
                    </xml>"; 
        //回复类型，如果为“text”，代表文本类型
        $msgType = "text";
        //格式化字符串
        $resultStr = sprintf($textTpl, $this->fromUsername, $this->toUsername, $this->time, $msgType, $contentStr);
        //把XML数据返回给手机端
        echo $resultStr;
    }
    // 设定模板消息场景值
    public $scene = [
        // 关注通知
        'gztz' => [
            'msg' => "",
            // 跳转的url
            'url' => '',
            'templateId' => ''
        ],

    ];

    /**
     * 发送模板消息
     * @return [type] [description]
     */
    public function sendTemplateMsg($openId, $templateId, $data, $token, $url)
    {
        // $token = $this->getToken();
        $wxurl = "https://api.weixin.qq.com/cgi-bin/message/template/send?access_token={$token}";
        $arr = [
            'touser'=>$openId,//用户open_id
            'template_id'=>$templateId,
            'url'=>$url,
            'data'=>$data,
            // [
            //     'name'=>['value'=>'hello', 'color'=>"#173177"],
            //     'age'=>['value'=>'17', 'color'=>"#173177"],
            //     'sex'=>['value'=>'男', 'color'=>"#173177"],
            // ],
        ];
        $postJson = json_encode($arr);
        $res = HttpHelper::httpCurl($wxurl, 'post', 'json', $postJson);
        return $res;
    }

    /**
     * 获取 token
     * @return [type] [description]
     */
    public function getToken()
    {
        $access_token = Yii::$app->cache->get('wx_zhshop_access_token');
        if(!$access_token){
            $access_token_url = 'https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid='.
                $this->wxconfig['zbshop']['app_id'].'&secret='.$this->wxconfig['zbshop']['app_secret'];
            $json_data = Helper::curlGet($access_token_url);
            $json_data = json_decode($json_data,true);
            $access_token = $json_data['access_token'];
            Yii::$app->cache->set('wx_zhshop_access_token', $access_token, 7200);
        }
        return $access_token ;
    }
    /**
     * 发送微信消息
     * @return [type] [description]
     */
    public function sendPubMessage($info)
    {
        $data = [];

        $data['first'] = ['value'=> $info['title'], 'color'=>"#173177"];
        $data['remark'] = ['value'=> $info['remark'], 'color'=>"#173177"];
        $url = $info['url'];
        $templateId = $this->scene['pub']['templateId'][$info['scene']];
        // 内容
        $data['keyword1'] = ['value'=> $info['content'], 'color'=>"#173177"];
        // 时间
        $data['keyword2'] = ['value'=> $info['time'], 'color'=>"#173177"];
        
        $token = $this->getToken();

        $openId = $info['openId'];
        $result = $this->sendTemplateMsg($openId, $templateId, $data, $token, $url);

        // 保存发送的数据
        // $params = [
        //     '`openid`' => $openId,
        //     '`type`' => 'pub',
        //     '`orderid`' => '',
        //     '`from`' => $info['scene'],
        // ];
        // $this->saveMsgInfo($params);
        return $result;
    }

    //定义checkSignature
    private function checkSignature($token)
    {
        // you must define TOKEN by yourself
        //判断TOKEN密钥是否定义
        if (empty($token)) {
            //如果没有定义抛出异常
            throw new Exception('TOKEN is not defined!');
        }
        //接收微信加密签名
        $signature = $_GET["signature"];
        //接收时间戳
        $timestamp = $_GET["timestamp"];
        //接收随机数
        $nonce = $_GET["nonce"];
        //把相关参数组装为数组（密钥、时间戳、随机数）
        $tmpArr = array($token, $timestamp, $nonce);
        // use SORT_STRING rule
        //通过字典法进行排序
        sort($tmpArr, SORT_STRING);
        //把排序后的数组转化字符串
        $tmpStr = implode( $tmpArr );
        //通过哈希算法对字符串进行加密操作
        $tmpStr = sha1( $tmpStr );
        
        //与加密签名进行对比
        if( $tmpStr == $signature ){
            return true;
        }else{
            return false;
        }
    }
    /**
     * 通过code来获取openid
     * @return [type] [description]
     */
    public function getOpenid()
    {
        $code = Yii::$app->request->get('code', '');
        // 如果是第一次则获取openid,防止刷新报错
        $openid = Yii::$app->request->cookies->getValue('openid');

        if (empty($openid)) {
            $appId = Yii::$app->params['wxconfig']['zbshop']['app_id'];
            $appSecret = Yii::$app->params['wxconfig']['zbshop']['app_secret'];
            // 通过code获取网页授权access_token和
            $oauth2Url = "https://api.weixin.qq.com/sns/oauth2/access_token?appid={$appId}&secret={$appSecret}&code={$code}&grant_type=authorization_code";

            $oauth2 = HttpHelper::httpCurl($oauth2Url);
            // 跳转
            if (!isset($oauth2['openid'])) {
                return false;
            }
            // 把openid存入到cookie
            $cookies = Yii::$app->response->cookies;
            $cookie = new Cookie(['name' => 'openid', 'value' => $oauth2['openid']]);
            $cookies->add($cookie);
            return $oauth2['openid'];
        }
        return $openid;
    }
}
