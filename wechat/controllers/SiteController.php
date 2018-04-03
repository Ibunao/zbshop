<?php
namespace wechat\controllers;

use Yii;

use yii\web\Controller;
use wechat\controllers\bases\BaseController;
use wechat\helpers\WchatHelper;
use common\models\CustomerModel;
use common\models\AgentUserModel;
use yii\web\Cookie;
/**
 * Site controller
 */
class SiteController extends BaseController
{

    /**
     * @inheritdoc
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    /**
     * 响应微信
     *
     * @return mixed
     */
    public function actionIndex()
    {
        // 验证服务器
        // (new WchatHelper)->valid();
        // 消息回复
        (new WchatHelper)->responseMsg();
    }
    /**
     * 已关注用户静默获取openid
     * @return [type] [description]
     */
    public function actionGetOpenid()
    {
        $openid = Yii::$app->request->cookies->getValue('openid');
        if (!empty($openid)) {
            $this->redirect('/site/signup');
        }
        $appId = Yii::$app->params['wxconfig']['zbshop']['app_id'];
        $state = 1; // 1 为正式
        // 回调地址
        $redirect_uri = urlencode('http://wx.quutuu.com/site/signup');
        $url = "https://open.weixin.qq.com/connect/oauth2/authorize?appid={$appId}&redirect_uri={$redirect_uri}&response_type=code&scope=snsapi_base&state={$state}#wechat_redirect";
        header("Location:".$url);
    }
    /**
     * 注册
     * @return [type] [description]
     */
    public function actionSignup()
    {
        // 如果已经
        $agentId = Yii::$app->request->cookies->getValue('agentId');
        if (!empty($agentId)) {
            $agent = (new AgentUserModel())->findOne(['id' => $agentId]);
            // 如果不是被拒绝
            if ($agent->status != 3) {
                $this->redirect('/site/join-us');
            }
        }
        // 获取openid
        $openid = (new WchatHelper)->getOpenid();
        // 如果已经注册过，则为更新数据
        if (!empty($openid)) {
            $model = (new AgentUserModel())->find()
                ->where(['openid' => $openid])
                ->one();
        }
        if (!isset($model) || empty($model)) {
            $model = new AgentUserModel();
        }
        
        if (Yii::$app->request->isPost) {
            $model->load(Yii::$app->request->post());
            $model->openid = $openid;
            if ($user = $model->signup()) {
                // 把openid存入到cookie
                $cookies = Yii::$app->response->cookies;
                $cookie = new Cookie(['name' => 'agentId', 'value' => $user->id]);
                $cookies->add($cookie);
                $this->redirect('/site/join-us');
                // 暂时不写登陆功能
                // if (Yii::$app->getUser()->login($user)) {
                    // return $this->redirect('/site/join-us');
                // }
            }
        }

        return $this->render('signup', [
            'model' => $model,
        ]);
    }
    /**
     * 加入我们页面
     * @return [type] [description]
     */
    public function actionJoinUs()
    {
        $sceneId = Yii::$app->request->cookies->getValue('agentId');
        $isJion = false;
        $src = '';
        if (is_file(Yii::$app->params['shareImagesPath'].$sceneId.'.png')) {
            $isJion = true;
            $src = Yii::$app->params['shareImagesUrl'].$sceneId.'.png';
        }
        $agent = (new AgentUserModel())->findOne(['id' => $sceneId]);

        return $this->render('jion', ['isJion' => $isJion, 'src' => $src, 'status' => $agent->status]);
    }
    /**
     * 获取分享的图片
     * @return [type] [description]
     */
    public function actionGetShareImage()
    {
        
        Yii::$app->response->format = 'json';
        $sceneId = Yii::$app->request->cookies->getValue('agentId');
        $agent = (new AgentUserModel())->findOne(['id' => $sceneId]);
        if ($agent->status != 2) {
            return ['code' => 400, 'msg' => '审核未通过'];
        }
        $result = (new WchatHelper)->getQuickMark($sceneId);
        if (empty($result)) {
            return ['code' => 400];
        }
        
        return ['code' => 200, 'imgUrl' => Yii::$app->params['shareImagesUrl'].$sceneId.'.png'];
    }
    public function actionUpload()
    {
        Yii::$app->response->format = 'json';

        // var_dump($_FILES);exit;
        // 允许的格式
        $typeArr = ['image/jpeg', 'image/png', 'image/jpg'];
        $fileType=$_FILES['abc']['type'];  
        if(!in_array($fileType, $typeArr)) { 
            return ['code' => 400, 'msg' => '图片格式错误'.$fileType];
        }  
  
        if(is_uploaded_file($_FILES['abc']['tmp_name'])) {  
            $uploadedFile = $_FILES['abc']['tmp_name'];  
      
            $path = Yii::$app->params['agentImagesPath'];  
            //判断该用户文件夹是否已经有这个文件夹  
            if(!file_exists($path)) {  
                mkdir($path);  
            }  
      
            $fileTrueName=$_FILES['abc']['name']; 
            $img = time().rand(1,1000).substr($fileTrueName,strrpos($fileTrueName,"."));
            $moveToFile=$path.$img;  
            if(move_uploaded_file($uploadedFile, iconv("utf-8","gb2312",$moveToFile))) {  
                return ['code' => 200, 'msg' => '上传成功', 'url' => Yii::$app->params['agentImagesUrl'].$img]; 
            } else {  
                return ['code' => 400, 'msg' => '上传失败'];
            }  
        } else {  
            return ['code' => 400, 'msg' => '你从哪里来？'];
        }
    }
    /**
     * 创建菜单
     * @return [type] [description]
     */
    public function actionMenu()
    {
        (new WchatHelper)->menu();
    }

    public function actionTest()
    {
        // echo Yii::$app->basePath;

        // 发送模板消息
        /*$info['title'] = '恭喜，邀请成功!';
        $info['content'] = '二维码邀请';
        $info['remark'] = '恭喜你，距亿万富翁又进一步';
        $info['openId'] = 'oCSMd0obtXDxrhvuExl0J14jzaSQ';
        $info['url'] = '';
        (new WchatHelper)->sendShareMessage($info);*/

        // 获取缓存
        // $cache = Yii::$app->cache->get('debug');
        // var_dump($cache);
        phpinfo();
    }
}
