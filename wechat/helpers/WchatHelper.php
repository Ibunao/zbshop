<?php
namespace wechat\helpers;
use Yii;
use yii\base\Object;
use wechat\helpers\HttpHelper;
use yii\web\Cookie;
use common\models\CustomerModel;
use common\models\WxOther;
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
        // $postStr = $GLOBALS["HTTP_RAW_POST_DATA"];
        $postStr = file_get_contents("php://input");
        // file_put_contents(Yii::$app->params['shareImagesPath'].'wx.md', $postStr);
        // $postStr = "<xml><URL><![CDATA[http://wx.quutuu.com]]></URL><ToUserName><![CDATA[adfasdafasdfadfas]]></ToUserName><FromUserName><![CDATA[afdadfasdfasdfasfasd]]></FromUserName><CreateTime>1111132323</CreateTime><MsgType><![CDATA[event]]></MsgType><Event><![CDATA[scan]]></Event><Latitude></Latitude><Longitude></Longitude><Precision></Precision><MsgId>121232323</MsgId></xml>";

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
                // $this->sendText('欢迎加入趣途文化！');
                // 发送图文信息
                $this->sendImageText();
                // 扫场景二维码进来的
                if ($this->postObj->EventKey) {
                    $index = strrpos($this->postObj->EventKey, '_');
                    $sceneId = substr($this->postObj->EventKey, $index+1);
                    // 获取unionid
                    $openid = (string) $this->fromUsername;
                    $result = $this->getUserInfo($openid);
                    $unionid = isset($result['unionid'])?$result['unionid']:'';
                    (new CustomerModel)->Attention($sceneId, $openid, true, $unionid);
                }
                // $this->sendImageText();
                break;
            // 关注过扫码的情况
            case 'scan':
                // $this->sendText('你已经关注过了');
                // $this->sendText($this->postObj->EventKey);
                if ($this->postObj->EventKey) {
                    (new CustomerModel)->Attention($this->postObj->EventKey, $this->fromUsername);
                }
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
        $data = (new WxOther)->getItems();
        if (empty($data)) {
            $this->sendText('欢迎加入趣途文化！');return;
        }
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
        $count = count($data);
        //组装Articles节点信息
        $str = '<Articles>';
        foreach ($data as $key => $item) {
            $item['img'] = Yii::$app->params['adminUrl'].$item['img'];
            $str .= "<item>
                    <Title><![CDATA[{$item['title']}]]></Title> 
                    <Description><![CDATA[{$item['desc']}]]></Description>
                    <PicUrl><![CDATA[{$item['img']}]]></PicUrl>
                    <Url><![CDATA[{$item['url']}]]></Url>
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
        'share' => [
            'msg' => "",
            // 跳转的url
            'url' => '',
            'templateId' => 'gJVXL_hY1k4BSsW74cS-tC_CX1G5KwHzr257QaCOnbQ'
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
        // $access_token = Yii::$app->cache->get('wx_zhshop_access_token');
        $result = Yii::$app->db->createCommand('SELECT token,time FROM wx_token WHERE id=1')
           ->queryOne();
        if (empty($result) || $result['time'] < time() - 7150) {
            $access_token_url = 'https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid='.
                $this->wxconfig['zbshop']['app_id'].'&secret='.$this->wxconfig['zbshop']['app_secret'];
            $json_data = HttpHelper::httpCurl($access_token_url);
            $access_token = $json_data['access_token'];
            // $result = Yii::$app->cache->set('wx_zhshop_access_token', $access_token, 7200);
            if (empty($result)) {
                Yii::$app->db->createCommand()->insert('wx_token', [
                    'token' => $access_token,
                    'time' => time(),
                ])->execute();
            }else{
                Yii::$app->db->createCommand()->update('wx_token', ['token' => $access_token, 'time' => time()], 'id = 1')->execute();
            }
        }else{
            $access_token = $result['token'];
        }
        
        // var_dump($access_token);
        return $access_token ;
    }
    /**
     * 发送微信分享关注消息
     * @return [type] [description]
     */
    public function sendShareMessage($info)
    {
        $data = [];

        $data['first'] = ['value'=> $info['title'], 'color'=>"#173177"];
        $data['remark'] = ['value'=> $info['remark'], 'color'=>"#173177"];
        $url = $info['url'];
        $templateId = $this->scene['share']['templateId'];
        // 内容
        $data['keyword1'] = ['value'=> $info['content'], 'color'=>"#173177"];
        // 时间
        $data['keyword2'] = ['value'=> date("Y-m-d H:i:s"), 'color'=>"#173177"];
        
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
            // var_dump($oauth2);exit;
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
    /**
     * 创建目录
     * @return [type] [description]
     */
    public function menu()
    {
        $token = $this->getToken();
        $url = "https://api.weixin.qq.com/cgi-bin/menu/create?access_token={$token}";
        //定义菜单   post请求参数
        $postArr = [
            'button'=>[
                
                // 第一个一级菜单，包含二级菜单
                [
                    'name'=>urlencode('冬夏令营'),
                    //定义子菜单
                    'sub_button'=>[
                        [
                            'name'=>urlencode('冬令营'),
                            'type'=>'view',
                            'url'=>'http://mp.weixin.qq.com/mp/homepage?__biz=MzUyNTEyNDg3Mw==&hid=2&sn=a45a16a04605609835762722a6cc1aaf&scene=18#wechat_redirect',
                        ],
                        // url跳转按钮
                        [
                            'name'=>urlencode('夏令营'),
                            'type'=>'view',
                            'url'=>'https://mp.weixin.qq.com/mp/homepage?__biz=MzUyNTEyNDg3Mw%3D%3D&hid=9&sn=40f8ac6ade15e3e9edecdefa02ec2882',
                        ],
                        // 
                        [
                            'name'=>urlencode('城市定向赛'),
                            'type'=>'view',
                            'url'=>'http://mp.weixin.qq.com/mp/homepage?__biz=MzUyNTEyNDg3Mw==&hid=8&sn=ef01d265b2fe3e2d33af1e983f71f9aa#wechat_redirect',
                        ],
                    ]
                ],
                //第二个一级菜单
                [
                    'name'=>urlencode('免费漂流'),//这样防止转json中文会成\uxxx的形式
                    'type'=>'view',
                    'url'=>'http://wx.quutuu.com/site/get-openid',
                        // // url跳转按钮
                        // [
                        //     'name'=>urlencode('教练介绍'),
                        //     'type'=>'view',
                        //     'url'=>'http://mp.weixin.qq.com/mp/homepage?__biz=MzUyNTEyNDg3Mw==&hid=5&sn=9fc9a71e10f42087cd0a721c58842537&scene=18#wechat_redirect',
                        // ],
                        // // url跳转按钮
                        // [
                        //     'name'=>urlencode('公司制定'),
                        //     'type'=>'view',
                        //     'url'=>'https://v.qq.com/x/page/x0546xexr9l.html',
                        // ],
                ],
                // 第三个一级菜单
                [
                    'name'=>urlencode('趣游途优'),
                    //定义子菜单
                    'sub_button'=>[
                        [
                            'name'=>urlencode('\\"趣\\"活动'),
                            'type'=>'view',
                            'url'=>'http://mp.weixin.qq.com/mp/homepage?__biz=MzUyNTEyNDg3Mw==&hid=6&sn=22c681745df89fb900739ae8b6c0d4d5&scene=18#wechat_redirect',
                        ],
                        // url跳转按钮
                        [
                            'name'=>urlencode('\\"趣\\"交友'),
                            'type'=>'view',
                            'url'=>'http://mp.weixin.qq.com/mp/homepage?__biz=MzUyNTEyNDg3Mw==&hid=7&sn=cf28b96c96a3fe527250d9f47b5cf2fe&scene=18#wechat_redirect',
                        ],
                        [
                            'name'=>urlencode('\\"趣\\"商城'),
                            'type'=>'miniprogram',
                            // 备用网页路径
                            'url'=>'http://mp.weixin.qq.com/bizmall/mallshelf?id=&t=mall/list&biz=MzUyNTEyNDg3Mw==&shelf_id=1&showwxpaytitle=1#wechat_redirect',
                            "appid" => Yii::$app->params['xcxid'],
                            "pagepath" => "pages/index/index",
                        ],
                        // [
                        //     'name'=>urlencode('加入我们'),
                        //     'type'=>'view',
                        //     'url'=>'http://wx.quutuu.com/site/get-openid',
                        // ],
                    ]
                ],
            ]
        ];
        $postJson = urldecode(json_encode($postArr));
        var_dump($postJson);
        $res = HttpHelper::httpCurl($url, 'post', 'json', $postJson);
        var_dump($res);
    }
    /**
     * 获取场景二维码
     * @param  string $value [description]
     * @return [type]        [description]
     */
    public function getQuickMark($sceneId)
    {
        if (empty($sceneId)) {
            return false;
        }
        // 如果已经下载过了
        if (is_file(Yii::$app->params['shareImagesPath'].$sceneId.'.png')) {
            return true;   
        }
        $token = $this->getToken();
        $url = 'https://api.weixin.qq.com/cgi-bin/qrcode/create?access_token='.$token;
        $postArr = [
            'action_name' => "QR_LIMIT_SCENE", 
            "action_info" => [
                "scene" => ["scene_id" => $sceneId]
            ]
        ];
        $postJson = json_encode($postArr);
        $result = HttpHelper::httpCurl($url, 'post', 'json', $postJson);
        if (isset($result['ticket']) && !empty($result['ticket'])) {
            $fileUrl = 'https://mp.weixin.qq.com/cgi-bin/showqrcode?ticket='.$result['ticket'];
            $content = file_get_contents($fileUrl);
            $result = file_put_contents(Yii::$app->params['shareImagesPath'].$sceneId.'.png', $content);
            if (!empty($result)) {
                return true;
            }
        }
        return false;
    }
    public function getUserInfo($openid)
    {
        $token = $this->getToken();
        $url = "https://api.weixin.qq.com/cgi-bin/user/info?access_token=".$token."&openid=".$openid."&lang=zh_CN";
        $json_data = HttpHelper::httpCurl($url);
        return $json_data;
    }
}
