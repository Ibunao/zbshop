<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%goods_specifications}}".
 *
 * @property string $id 主键
 * @property int $g_id 商品id
 * @property string $sids 规格id组合逗号分隔
 * @property string $snames 规格值组合逗号分隔
 * @property string $s_v_value 规格值组合逗号分隔
 * @property string $s_v_ids 规格值id组合，如 10:30 表示黄色:xl
 * @property string $image 该规格组合的图片
 * @property string $price 该规格组合的价格
 * @property int $barcode 商品条形码
 * @property string $store 库存
 * @property int $disabled 是否有效0:有效、1:无效
 */
class GoodsSpecificationsModel extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%goods_specifications}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['g_id', 's_v_ids', 'store'], 'required'],
            [['g_id', 'barcode', 'store'], 'integer'],
            [['price'], 'number'],
            [['sids'], 'string', 'max' => 30],
            [['snames', 's_v_value', 's_v_ids'], 'string', 'max' => 255],
            [['image'], 'string', 'max' => 60],
            [['disabled'], 'string', 'max' => 3],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'g_id' => 'G ID',
            'sids' => 'Sids',
            'snames' => 'Snames',
            's_v_value' => 'S V Value',
            's_v_ids' => 'S V Ids',
            'image' => 'Image',
            'price' => 'Price',
            'barcode' => 'Barcode',
            'store' => 'Store',
            'disabled' => 'Disabled',
        ];
    }
}
