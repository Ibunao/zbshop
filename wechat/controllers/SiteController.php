<?php
namespace wechat\controllers;

use Yii;

use yii\web\Controller;
use wechat\controllers\bases\BaseController;
use wechat\helpers\WchatHelper;
use common\models\CustomerModel;
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
        $customerId = Yii::$app->request->cookies->getValue('customerId');
        if (!empty($customerId)) {
            $this->redirect('/site/join-us');
        }
        // 获取openid
        $openid = (new WchatHelper)->getOpenid();
        // 如果已经注册过，则为更新数据
        if (!empty($openid)) {
            $model = (new CustomerModel())->find()
                ->where(['openid' => $openid])
                ->one();
        }
        if (!isset($model) || empty($model)) {
            $model = new CustomerModel();
        }
        
        if (Yii::$app->request->isPost) {
            $model->load(Yii::$app->request->post());
            $model->openid = $openid;
            if ($user = $model->signup()) {
                // 把openid存入到cookie
                $cookies = Yii::$app->response->cookies;
                $cookie = new Cookie(['name' => 'customerId', 'value' => $user->id]);
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
        $sceneId = Yii::$app->request->cookies->getValue('customerId');
        $isJion = false;
        $src = '';
        if (is_file(Yii::$app->params['shareImagesPath'].$sceneId.'.png')) {
            $isJion = true;
            $src = Yii::$app->params['shareImagesUrl'].$sceneId.'.png';
        }
        return $this->render('jion', ['isJion' => $isJion, 'src' => $src]);
    }
    /**
     * 获取分享的图片
     * @return [type] [description]
     */
    public function actionGetShareImage()
    {
        Yii::$app->response->format = 'json';
        $sceneId = Yii::$app->request->cookies->getValue('customerId');
        $result = (new WchatHelper)->getQuickMark($sceneId);
        if (empty($result)) {
            return ['code' => 400];
        }
        return ['code' => 200, 'imgUrl' => Yii::$app->params['shareImagesUrl'].$sceneId.'.png'];
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
        $cache = Yii::$app->cache->get('debug');
        var_dump($cache);
    }
}
