<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%goods_attributes}}".
 *
 * @property string $id 主键
 * @property int $g_id 商品id
 * @property string $a_id 属性id
 * @property string $avalue 属性值
 */
class GoodsAttributesModel extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%goods_attributes}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['g_id', 'a_id', 'avalue'], 'required'],
            [['g_id', 'a_id'], 'integer'],
            [['avalue'], 'string', 'max' => 30],
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
            'a_id' => 'A ID',
            'avalue' => 'Avalue',
        ];
    }
}
