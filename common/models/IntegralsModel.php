<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%integrals}}".
 *
 * @property string $id
 * @property string $old 使用前的积分
 * @property int $change 使用或赠送的积分
 * @property string $new 新积分
 * @property string $remark 使用/获取途径说明
 * @property string $create_at
 */
class IntegralsModel extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%integrals}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['old', 'change', 'new', 'create_at'], 'integer'],
            [['change', 'new', 'create_at'], 'required'],
            [['remark', 'openid'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'old' => 'Old',
            'change' => 'Change',
            'new' => 'New',
            'remark' => 'Remark',
            'create_at' => 'Create At',
        ];
    }
}
