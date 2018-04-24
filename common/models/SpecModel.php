<?php

namespace common\models;

use Yii;
use yii\db\Query;
/**
 * This is the model class for table "{{%specifications}}".
 *
 * @property string $id 属性id
 * @property string $name 规格名
 * @property int $need_img 0：不需要图片、1：需要图片
 * @property int $disabled 0：有效、1：无效
 */
class SpecModel extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%specifications}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['name'], 'string', 'max' => 60],
            [['disabled'], 'string', 'max' => 3],
            ['need_img', "number"],
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
            'need_img' => '是否为主规格',
            'disabled' => 'Disabled',
        ];
    }
    public function setSpec($cid, $spec)
    {
        $model = self::findOne(['name' => $spec['name']]);
        // 如果没有则新加
        if (empty($model)) {
            $this->name = $spec['name'];
            $this->need_img = $spec['main'] == "false"?0:1;
            if ($this->save()) {
                // 执行插入操作
                $connection  = Yii::$app->db;
                $result = $connection->createCommand()->insert('shop_categories_specifications', [
                     'c_id' => $cid,
                     's_id' => $this->id,
                 ])->execute();
                if (!$result) {
                    return false;
                }
                // 批量插入规格值
                $data = [];
                foreach ($spec['values'] as $value) {
                    $data[] = ['name' => $value, 'p_id' => $this->id];
                }
                $result = $connection->createCommand()->batchInsert('shop_specification_values', 
                    ['name', "p_id"], 
                    $data)
                ->execute(); 
                if (!$result) {
                    return false;
                }
                return true;
            }else{
                var_dump($this->errors);exit;
                return false;
            }
        // 如果已经存在就更新操作
        }else{
            $spec['main'] = $spec['main'] == "false"?0:1;
            if ($model->need_img != $spec['main']) {
                $model->need_img = $spec['main'];
                $model->save(false);
            }
            $specs = (new Query)->from(['shop_specification_values'])
                ->select(['name'])
                ->where(["p_id" => $model->id])
                ->all();
            $names = [];
            foreach ($specs as $key => $value) {
                $names[] = $value['name'];
            }
            // 批量插入规格值
            // 如果不存在的直接插入，如果已经存在的就更新一下
            $data = [];
            $update = [];
            $delete = [];
            foreach ($spec['values'] as $value) {
                if (in_array($value, $names)) {
                    $update[] = $value;
                }else{
                    $data[] = ['name' => $value, 'p_id' => $model->id];
                }
            }
            // 插入
            $result = Yii::$app->db->createCommand()->batchInsert('shop_specification_values', 
                ['name', "p_id"], 
                $data)
            ->execute(); 
            // 更新
            $result = Yii::$app->db->createCommand()->update('shop_specification_values', ['disabled' => 0], ['in', 'name', $update])->execute();
            // 更新
            $delete = array_diff($names, $update);
            $result = Yii::$app->db->createCommand()->update('shop_specification_values', ['disabled' => 1], ['in', 'name', $delete])->execute();
            return true;
        }
    }

    public function getSpecs($cid, $name)
    {
        // 为了方便，联表
        $result = (new Query)->select(['sv.id svid', 's.name sname', 's.need_img', 'sv.name name', 'cs.s_id s_id'])
            ->from('shop_categories_specifications cs')
            ->leftJoin('shop_specifications s', 's.id = cs.s_id')
            ->leftJoin('shop_specification_values sv', 'sv.p_id = s.id')
            ->where(['cs.c_id' => $cid, 'sv.disabled' => 0, 'cs.disabled' => 0, 's.disabled' => 0])
            ->filterWhere(['s.name' => $name])
            ->all();
        $response = [];
        foreach ($result as $item) {
            $response[$item['sname']][] = ['name' => $item['name'], 'main' => $item['need_img'], 'sid' => $item['s_id'], 'svid' => $item['svid']];
        }
        return $response;
    }
    public function deleteSpecs($cid, $sid)
    {
        $result = Yii::$app->db->createCommand()->update('shop_categories_specifications', ['disabled' => 1], ['c_id' => $cid, 's_id' => $sid])->execute();
        return $result;
    }
}
