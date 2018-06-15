<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%homepage}}".
 *
 * @property int $id
 * @property string $data 首页数据
 * @property int $uptime 更新时间
 */
class HomepageModel extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%homepage}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['data', 'uptime'], 'required'],
            [['uptime'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'data' => 'Data',
            'uptime' => 'Uptime',
        ];
    }
}
