<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%attributes}}".
 *
 * @property string $id 属性id
 * @property string $c_id 分类id
 * @property string $name 属性名
 * @property int $disabled 0：有效、1：无效
 */
class AttributesModel extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%attributes}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['c_id', 'name'], 'required'],
            [['c_id'], 'integer'],
            [['name'], 'string', 'max' => 60],
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
            'c_id' => 'C ID',
            'name' => 'Name',
            'disabled' => 'Disabled',
        ];
    }
    /**
     * 通过分类id获取分类下的属性
     * @param  [type] $cid 分类id
     * @return [type]      [description]
     */
    public function getAttributes($cid)
    {
        $result = self::find()
            ->select(['id', 'name'])
            ->where(['c_id' => $cid])
            ->asArray()
            ->indexBy('id')
            ->all()
        foreach ($result as $key => $value) {
            $result[$key] = $value['name'];
        }
        return $result;
    }
}
