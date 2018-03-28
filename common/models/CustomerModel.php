<?php

namespace common\models;

use Yii;

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
 * @property int $is_join 0:没有加入，1：为加入了我们，申请了分享码
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
            [['is_join'], 'integer', 'max' => 3],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'username' => 'Username',
            'mobile' => '手机号',
            'password' => '输入密码',
            'password2' => '再次输入密码码',
            'openid' => 'Openid',
            'unionid' => 'Unionid',
            'share_id' => 'Share ID',
            'is_join' => 'Is Join',
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
            return null;
        }
        $this->username;
        $this->mobile;
        $this->password;
        $this->created_at = time();
        return $this->save() ? $this : null;
    }
}
