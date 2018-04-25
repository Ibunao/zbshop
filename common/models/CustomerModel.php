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
            [['openid', 'unionid'], 'string', 'max' => 120],
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
    public function Attention($sceneId, $openid, $check = false)
    {
        $success = false;
        if ($check) {
            $model = self::findOne([
                'openid' => $openid,
            ]);
            if (!$model) {
                $this->openid = $openid;
                $this->created_at = time();
                $this->share_id = $sceneId;
                $result = $this->save(false);
                if ($result) {
                    $success = true;
                }
            }
        }
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
     * 获取代理人
     * @return [type] [description]
     */
    public function getAgentName()
    {
        var_dump(AgentUserModel::findOne(['id' => $this->share_id]));exit;
        return AgentUserModel::findOne(['id' => $this->share_id])->username;
    }

}
