<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%order_items}}".
 *
 * @property int $id
 * @property int $orderid 订单id
 * @property int $goodsid 商品id
 * @property int $specid 商品的规格值id
 * @property string $price 价格
 * @property int $num 数量
 * @property string $specvalue 规格值
 */
class OrderItemsModel extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%order_items}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['orderid', 'goodsid', 'specid', 'price', 'num'], 'required'],
            [['orderid', 'goodsid', 'specid', 'num'], 'integer'],
            [['price'], 'number'],
            [['specvalue'], 'string', 'max' => 120],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'orderid' => 'Orderid',
            'goodsid' => 'Goodsid',
            'specid' => 'Specid',
            'price' => 'Price',
            'num' => 'Num',
            'specvalue' => 'Specvalue',
        ];
    }
}
