<?php

namespace common\models;

use Yii;
use wechat\helpers\WchatHelper;
use common\models\AgentUserModel;
/**
 * This is the model class for table "{{%customer}}".
 *
 * @property string $id 主键
 * @property string $username 用户名
 * @property string $mobile 手机号
 * @property string $password 用户密码
 * @property string $openid openid
 * @property string $unionid unionid
 * @property string $share_id 分享人的id
 * @property string $created_at
 * @property string $updated_at
 */
class CustomerModel extends \yii\db\ActiveRecord
{
    // 再次输入验证码
    public $password2;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%customer}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['mobile', 'password', 'password2'], 'required'],
            ['password2', 'compare', 'compareAttribute'=>'password'],
            [['mobile', 'share_id', 'created_at', 'updated_at'], 'integer'],
            [['username', 'password'], 'string', 'max' => 60],
            [['openid', 'openid1', 'unionid'], 'string', 'max' => 120],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => '用户id',
            'username' => 'Username',
            'mobile' => '手机号',
            'password' => '输入密码',
            'password2' => '再次输入密码码',
            'openid' => 'Openid',
            'unionid' => 'Unionid',
            'share_id' => 'Share ID',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * 注册
     *
     * @return User|null the saved model or null if saving fails
     */
    public function signup()
    {
        if (!$this->validate()) {
            // var_dump($this->errors, $this->openid);
            return null;
        }
        $this->created_at = time();
        return $this->save() ? $this : null;
    }
    /**
     * 关注的时候
     * @param string $value [description]
     */
    public function Attention($sceneId, $openid, $check = false, $unionid = '')
    {
        $success = false;
        if ($check) {
            // 如果存在unionid(先登录了小程序，后关注微信公众号)，则是更新操作
            $model = self::find()
                ->where(['openid' => $openid])
                ->orFilterWhere(['unionid' => $unionid])
                ->one();
            if (!$model) {
                $this->openid = $openid;
                $this->created_at = time();
                $this->share_id = $sceneId;
                if (!empty($unionid)) {
                    $this->unionid = $unionid;
                }
                $result = $this->save(false);
                if ($result) {
                    $success = true;
                }
            // 如果存在unionid，更新,不带场景值
            }elseif ($model->unionid) {
                $model->openid = $openid;
                $this->save(false);
            }
        }
        // 给代理发送信息
        $model = AgentUserModel::findOne([
                'id' => $sceneId,
            ]);
        if (!$model) {
            return;
        }
        $info['title'] = '恭喜，邀请成功!';
        $info['content'] = '二维码邀请';
        $info['remark'] = '恭喜你，距亿万富翁又近一步';
        $info['openId'] = $model->openid;
        $info['url'] = '';
        if (!$success) {
            $info['title'] = 'sorry, sorry, sorry';
            $info['remark'] = '此用户已关注或曾关注过';
        }
        // 发送邀请成功通知。
        (new WchatHelper)->sendShareMessage($info);
    }
    /**
     * 根据unionid来绑定公众号openid和小程序的openid
     * @param  [type] $openid  小程序openid
     * @param  [type] $unionid 唯一标示
     * @return [type]          [description]
     */
    public function xcxAttention($openid, $unionid)
    {
        if ($unionid) {
            $model = self::find()
                ->where(['unionid' => $unionid])
                ->one();
            if (!$model) {
                $this->openid1 = $openid;
                $this->created_at = time();
                $this->unionid = $unionid;
                $result = $this->save(false);
            // 如果存在unionid，更新,不带场景值
            }else {
                $model->openid1 = $openid;
                $this->save(false);
            }
        }
    }

    /**
     * 获取代理人
     * @return [type] [description]
     */
    public function getAgentName()
    {
        $model = AgentUserModel::findOne(['id' => $this->share_id]);
        if ($model) {
            return $model->username;
        }
        return "自关注";
    }

}
