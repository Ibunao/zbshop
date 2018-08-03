<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "agent_user".
 *
 * @property int $id 主键
 * @property string $username 用户名
 * @property string $mobile 手机号
 * @property int $sex 0:女，1:男
 * @property int $age 年龄
 * @property string $password 用户密码
 * @property string $idcard_img1 身份证正面图片
 * @property string $idcard_img2 身份证反面图片
 * @property int $bankcard 银行卡号
 * @property string $bank 开户银行
 * @property string $area 代理区域
 * @property int $status 0:初始、1:提交审核、2:审核通过、3:审核拒绝
 * @property string $openid openid
 * @property string $unionid unionid
 * @property int $created_at
 * @property int $updated_at
 */
class AgentUserModel extends \yii\db\ActiveRecord
{
    // 再次输入验证码
    public $password2;
    // 临时存放图片
    public $idcard_imga;
    public $idcard_imgb;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'agent_user';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        // return [
        //     [['age', 'created_at', 'updated_at', 'status'], 'integer'],
        //     [['username', 'mobile', 'password', 'sex', 'password2', 'age', 'area', 'idcard_img1', 'idcard_img2', 'bankcard', 'bank'], 'required'],
        //     [['username', 'password', 'area'], 'string', 'max' => 60],
        //     [['mobile', 'bankcard'], 'string', 'max' => 30],
        //     [['sex'], 'string', 'max' => 3],
        //     [['idcard_img1', 'idcard_img2', 'bank', 'openid', 'unionid'], 'string', 'max' => 120],
        //     // [['status'], 'string', 'max' => 4],
        //     ['password2', 'compare', 'compareAttribute'=>'password'],
        // ];
        return [
            [['created_at', 'updated_at', 'status'], 'integer'],
            [['username', 'mobile','area'], 'required'],
            [['password', 'sex', 'password2', 'age', 'idcard_img1', 'idcard_img2', 'bankcard', 'bank'], 'safe'],
            [['username', 'area'], 'string', 'max' => 60],
            [['mobile'], 'string', 'max' => 30],
            [['openid', 'unionid'], 'string', 'max' => 120],
            // [['status'], 'string', 'max' => 4],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'username' => '姓名',
            'mobile' => '手机号',
            'sex' => '性别',
            'age' => '年龄',
            'password' => '密码',
            'password2' => '再次输入密码',
            'idcard_img1' => '身份证正面图片',
            'idcard_img2' => '身份证反面图片',
            'idcard_imga' => '身份证正面图片',
            'idcard_imgb' => '身份证反面图片',
            'bankcard' => '银行卡号',
            'bank' => '开户行',
            'area' => '区间',
            'status' => 'Status',
            'openid' => 'Openid',
            'unionid' => 'Unionid',
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
            var_dump($this->errors, $this->openid);
            return null;
        }
        $this->created_at = time();
        $this->status = 2;
        return $this->save() ? $this : null;
    }

    /**
     * 获取所有代理商
     * @return [type] [description]
     */
    public static function getAgents()
    {
        $result = AgentUserModel::find()
            ->select(['id', 'username'])
            ->asArray()
            ->indexBy('id')
            ->all();
        if ($result) {
            foreach ($result as $key => $value) {
                $result[$key] = $value['username'];
            }
            return $result;
        }
        return [];
    }
    /**
     * 获取所有代理商名字和openid
     * @return [type] [description]
     */
    public static function getAgentsOpenid()
    {
        $result = AgentUserModel::find()
            ->select(['username', 'openid'])
            ->asArray()
            ->indexBy('openid')
            ->all();
        if ($result) {
            foreach ($result as $key => $value) {
                $result[$key] = $value['username'];
            }
            return $result;
        }
        return [];
    }
}
