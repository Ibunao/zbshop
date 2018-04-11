<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%goods_others}}".
 *
 * @property string $id 主键id
 * @property string $value 图片或描述
 * @property string $g_id 商品id
 * @property int $type 类型 1：商品详情描述信息，2：商品详情图，3：商品其他图
 */
class GoodsOthersModel extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%goods_others}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['value', 'g_id', 'type'], 'required'],
            [['g_id'], 'integer'],
            [['value'], 'string', 'max' => 300],
            [['type'], 'string', 'max' => 3],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'value' => 'Value',
            'g_id' => 'G ID',
            'type' => 'Type',
        ];
    }
}
