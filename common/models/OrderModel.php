<?php

namespace common\models;

use Yii;
use common\models\TempModel;
/**
 * This is the model class for table "{{%order}}".
 *
 * @property int $id
 * @property string $openid 小程序openid
 * @property string $order_id 订单id
 * @property string $pay_id 支付订单号
 * @property string $price 实际价格
 * @property string $express_fee 邮费
 * @property int $integrals 使用的积分
 * @property string $deduction 积分抵扣金额
 * @property string $pay_price 支付价格
 * @property int $status 支付状态：1：待支付，2：支付成功
 * @property string $remark 订单的备注
 * @property string $name 名字for快递
 * @property string $mobile 电话号for快递
 * @property string $address 地址for快递
 * @property int $create_at 创建时间
 * @property int $update_at 创建时间
 */
class OrderModel extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%order}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'openid', 'order_id', 'price', 'express_fee', 'pay_price', 'name'], 'required'],
            [['id', 'order_id', 'integrals', 'status', 'create_at', 'update_at'], 'integer'],
            [['price', 'express_fee', 'deduction', 'pay_price'], 'number'],
            [['openid', 'remark'], 'string', 'max' => 120],
            [['pay_id'], 'string', 'max' => 60],
            [['name', 'mobile'], 'string', 'max' => 30],
            [['address'], 'string', 'max' => 130],
            [['id'], 'unique'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'openid' => 'Openid',
            'order_id' => 'Order ID',
            'pay_id' => 'Pay ID',
            'price' => 'Price',
            'express_fee' => 'Express Fee',
            'integrals' => 'Integrals',
            'deduction' => 'Deduction',
            'pay_price' => 'Pay Price',
            'status' => 'Status',
            'remark' => 'Remark',
            'name' => 'Name',
            'mobile' => 'Mobile',
            'address' => 'Address',
            'create_at' => 'Create At',
            'update_at' => 'Update At',
        ];
    }
    public function setOrder($openid, $data)
    {
        $orderId = (new TempModel)->buildOrderNo();
    }
}
