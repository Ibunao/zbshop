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
            [['openid', 'order_id', 'price', 'express_fee', 'pay_price', 'name'], 'required'],
            [['order_id', 'integrals', 'status', 'create_at', 'update_at'], 'integer'],
            [['price', 'express_fee', 'deduction', 'pay_price'], 'number'],
            [['openid', 'remark'], 'string', 'max' => 120],
            [['pay_id'], 'string', 'max' => 60],
            [['name', 'mobile'], 'string', 'max' => 30],
            [['address'], 'string', 'max' => 130],
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
    /**
     * 创建订单
     * 验证金额的正确放在后台审核单子的时候
     * @param [type] $openid [description]
     * @param [type] $data   [description]
     */
    public function setOrder($openid, $data)
    {
        $this->openid = $openid;
        $orderId = (new TempModel)->buildOrderNo();
        $this->order_id = $orderId;
        $this->price = $data['original_price'];
        $this->express_fee = $data['express_fee'];
        $this->integrals = $data['useIntegrals'];
        $this->deduction = $data['deduction'];
        $this->pay_price = $data['totalPayment'];
        $this->remark = $data['orderRemark'];
        $this->status = 1;
        $this->name = $data['selectAddress']['address_info']['name'];
        $this->mobile = $data['selectAddress']['address_info']['contact'];
        $this->address = $data['selectAddress']['address_info']['detailAddress'];
        $this->create_at = time();
        if ($this->save()) {
            $orderItems = [];
            // 保存订单商品详情
            foreach ($data['goodsList'] as $key => $item) {
                $orderItems[] = ['orderid' => $orderId, 'goodsid' => $item['id'], 'specid' => $item['specId']?:0, 'price' => $item['price'], 'num' => $item['num'], 'specvalue' => $item['model_value_str']];
            }
            if (empty($orderItems)) {
                var_dump('商品为空');
                return false;
            }
            $result = Yii::$app->db 
                ->createCommand()
                ->batchInsert('shop_order_items', 
                    ['orderid', 'goodsid', 'specid', 'price', 'num', 'specvalue'], 
                    $orderItems)
                ->execute();
            if ($result) {
                // 保存订单信息。
                // Yii::$app->cache->set('order-'.$orderId, $data, 60*20*24);
                return $orderId;
            }
            var_dump('商品为空');
            return false;
        }
        var_dump($this->errors);
        return false;
    }
    /**
     * 获取订单信息
     * @param  [type] $orderId [description]
     * @return [type]          [description]
     */
    public function getOrderInfo($orderId)
    {
        return self::find()->where(['order_id' => $orderId])->asArray()->one();
    }
    /**
     * 微信支付返回数据更新订单表
     * @param  [type] $orderId  [description]
     * @param  [type] $openid   [description]
     * @param  [type] $payPrice [description]
     * @param  [type] $payId    [description]
     * @return [type]           [description]
     */
    public function wxNotify($orderId, $openid, $payPrice, $payId)
    {
        $model = self::find()->where(['order_id' => $orderId, 'openid' => $openid])->one();
        $model->pay_id = $payId;
        $model->status = $payPrice == $model->pay_price?2: ($payPrice == 1?2:4);
        $model->update_at = time();
        if ($model->save()) {
            // 更新积分。

            // 减库存
            
            return true;
        }
        var_dump($model->errors);
        return false;
    }
}
