<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%groups}}".
 *
 * @property string $id 分组id
 * @property string $name 组名
 */
class GroupsModel extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%groups}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['name'], 'string', 'max' => 30],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
        ];
    }
    /**
     * 获取所有的分组
     * @return [type] [description]
     */
    public function getGroups()
    {
        return self::find()
            ->asArray()
            ->all()
            ->indexBy('id');
    }
}
