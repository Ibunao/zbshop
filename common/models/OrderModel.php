<?php

namespace common\models;

use Yii;
use yii\db\Query;
use common\models\TempModel;
use common\models\CustomerModel;
use common\models\IntegralsModel;
use common\models\OrderItemsModel;
use common\models\GoodsSpecificationsModel;
use common\models\GoodsModel;
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
            'order_id' => '订单号',
            'pay_id' => 'Pay ID',
            'price' => '商品金额',
            'express_fee' => '邮费',
            'integrals' => '使用积分',
            'deduction' => '抵扣金额',
            'pay_price' => '支付价格',
            'status' => '支付状态',
            'ship_status' => '快递状态',
            'ship_no' => '快递号',
            'ship_type' => '快递类型',
            'remark' => '备注',
            'name' => '姓名',
            'mobile' => '电话号',
            'address' => '地址',
            'create_at' => '创建时间',
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
        // 因为会回调很多次，防止重复
        if (Yii::$app->cache->get('xcx-wxnotify'.$orderId)) {
            return;
        }
        $model = self::find()->where(['order_id' => $orderId, 'openid' => $openid])->one();
        $model->pay_id = $payId;
        $model->status = $payPrice == $model->pay_price?2: ($payPrice == 1?2:4);
        $model->update_at = time();
        if ($model->save()) {
            if ($model->status == 2) {
                // 更新积分。
                // 减去使用的积分
                $useIntegrals = $model->integrals;
                if ($useIntegrals) {
                    $customer = CustomerModel::findOne(['openid1' => $openid]);
                    $old = $customer->integrals;
                    $customer->updateCounters(['integrals' => -$useIntegrals]);
                    // 记录积分变动
                    $integralsModel = new IntegralsModel;
                    $integralsModel->old = $old;
                    $integralsModel->change = $useIntegrals;
                    $integralsModel->new = $old-$useIntegrals;
                    $integralsModel->remark = '支付使用积分';
                    $integralsModel->create_at = time();
                    $integralsModel->save();
                }
                
                // 加上支付成功赠送的积分
                // 用户信息主表更新
                // 测试时加10
                $integrals = 10;floor($model->pay_price/100);
                if ($integrals) {
                    $customer = CustomerModel::findOne(['openid1' => $openid]);
                    $old = $customer->integrals;
                    $customer->updateCounters(['integrals' => $integrals]);
                    // 记录积分变动
                    $integralsModel = new IntegralsModel;
                    $integralsModel->old = $old;
                    $integralsModel->change = $integrals;
                    $integralsModel->new = $old+$integrals;
                    $integralsModel->remark = '支付成功赠送积分';
                    $integralsModel->create_at = time();
                    $integralsModel->save();
                }
                // 库存
                $items = (new OrderItemsModel)->find()->select(['goodsid', 'specid', 'num'])->where(['orderid' => $orderId])->asArray()->all();
                $goods = [];
                foreach ($items as $key => $item) {
                    if (!isset($goods[$item['goodsid']])) {
                        $goods[$item['goodsid']] = 0;
                    }
                    $goods[$item['goodsid']] += $item['num'];
                    // 过滤掉单一规格的
                    if ($item['specid']) {
                        $goodsSpec = (new GoodsSpecificationsModel)->findOne($item['specid']);
                        $goodsSpec->updateCounters(['store' => -$item['num']]);
                    }
                }
                foreach ($goods as $gid => $num) {
                    $goods = (new GoodsModel)->findOne($gid);
                    $goods->updateCounters(['stores' => -$num]);
                }
            }
            Yii::$app->cache->set('xcx-wxnotify'.$orderId, true);
            return true;
        }
        var_dump($model->errors);
        return false;
    }
    /**
     * 获取用户的订单情况
     * @param  [type] $openid [description]
     * @return [type]         [description]
     */
    public function orderCondition($openid)
    {
        $result = ['daifu'=>0, 'daifa'=>0, 'daishou'=>0, 'daiping' => 0];
        $temp = self::find()->where(['openid' => $openid])->asArray()->all();
        if (empty($temp)) {
            return $result;
        }
        // 目前先只管代发货和确认收货的。
        foreach ($temp as $key => $item) {
            // 代发货
            if ($item['status'] == 2) {
                $result['daifa'] +=1;
            }
            // 待确认收货
            if ($item['status'] == 5) {
                $result['daishou'] +=1;
            }
        }
        return $result;
    }
    /**
     * 获取订单中心的某一类订单
     * @param  [type] $type [description]
     * @return [type]         [description]
     */
    public function daiInfo($where)
    {
        $result = [];
        $temp = self::find()->where($where)->asArray()->indexBy('order_id')->all();
        if (empty($temp)) {
            return $result;
        }
        $orderIds = [];
        // 目前先只管代发货和确认收货的。
        foreach ($temp as $key => $item) {
            $orderIds[] = $item['order_id'];
        }
        $goodsItem = (new Query)->from('shop_order_items oi')
            ->leftJoin('shop_goods g', 'g.id = oi.goodsid')
            ->where(['in', 'oi.orderid', $orderIds])
            ->all();
        foreach ($goodsItem as $key => $item) {
            $temp[$item['orderid']]['items'][] = $item;
        }
        sort($temp);
        return $temp;
    }
}
