<?php

namespace common\models;

use Yii;
use yii\web\UploadedFile;

/**
 * This is the model class for table "wx_other".
 *
 * @property int $id
 * @property string $url 连接地址
 * @property string $img 图片链接
 */
class WxOther extends \yii\db\ActiveRecord
{
    public $imgFile;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'wx_other';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['url'], 'required'],
            [['id'], 'integer'],
            [['url', 'img', 'title', 'desc'], 'string', 'max' => 255],
            [['order'], 'integer'],
            [['imgFile'], 'file', 'skipOnEmpty' => false, 'extensions' => 'png, jpg'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'url' => 'Url',
            'img' => 'Img',
        ];
    }
    public function upload()
    {
        $result = $this->imgFile->saveAs('./images/wx/' . $this->imgFile->baseName . '.' . $this->imgFile->extension);

        return '/images/wx/' . $this->imgFile->baseName . '.' . $this->imgFile->extension;
    }
    /**
     * 获取数据
     * @return [type] [description]
     */
    public function getItems()
    {
        $result = self::find()->orderBy('order')->asArray()->all();
        return $result;
    }
}
