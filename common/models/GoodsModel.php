<?php

namespace common\models;

use Yii;
use common\models\GoodsOthersModel;
/**
 * This is the model class for table "{{%goods}}".
 *
 * @property string $id 商品id
 * @property string $c_id 所属分类id
 * @property string $g_id 分组id
 * @property string $name 商品名称
 * @property int $spec 0:统一规格、1:多规格
 * @property string $wx_price 微信价格
 * @property string $market_price 市场价
 * @property string $stores 库存量
 * @property string $barcode 商品条码
 * @property string $image 主图
 * @property int $desc 商品描述0：不描述、1：描述，描述信息看关联表
 * @property string $limit 是否限购0:不限购、其它数值为限购
 * @property string $location 生产地或者发货地
 * @property int $is_bill 是否开发票0:不开、1：开
 * @property int $is_ repair 是否保修0:不保修、1:保修
 * @property int $is_on 是否上架0:不上架、1:上架
 * @property int $created_at
 * @property int $updated_at
 */
class GoodsModel extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%goods}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['c_id', 'g_id', 'name', 'wx_price', 'market_price', 'stores', 'barcode', 'image', 'desc', 'created_at'], 'required'],
            [['c_id', 'stores', 'limit', 'created_at', 'updated_at'], 'integer'],
            [['wx_price', 'market_price'], 'number'],
            [['name', 'image'], 'string', 'max' => 255],
            [['spec', 'desc', 'is_bill', 'is_repair', 'is_on'], 'integer'],
            [['barcode', 'location'], 'string', 'max' => 60],
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
            'g_id' => 'G ID',
            'name' => 'Name',
            'spec' => 'Spec',
            'wx_price' => 'Wx Price',
            'market_price' => 'Market Price',
            'stores' => 'Stores',
            'barcode' => 'Barcode',
            'image' => 'Image',
            'desc' => 'Desc',
            'limit' => 'Limit',
            'location' => 'Location',
            'is_bill' => 'Is Bill',
            'is_repair' => 'Is  Repair',
            'is_on' => 'Is On',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }
    public function addGoods($params)
    {
        $this->c_id = $params['category'];
        $this->g_id = implode(',', $params['groups']);
        $this->name = $params['goodsName'];
        $this->spec = empty($params['specification']) ? 0 : $params['specification'];
        $this->wx_price = empty($params['goodsWxPrice']) ? 0 : $params['goodsWxPrice'];
        $this->market_price = empty($params['goodsOriPrice']) ? 0 : $params['goodsOriPrice'];
        $this->stores = empty($params['goodsStore']) ? 0 : $params['goodsStore'];
        $this->barcode = empty($params['goodsNo']) ? '' : $params['goodsNo'];
        $this->image = $params['goodsMasterImg'][0];
        $this->desc = empty($params['describe']) ? 0 : $params['describe'];
        $this->limit = empty($params['limitationvalue']) ? 9999999 : $params['limitationvalue'];
        $this->location = empty($params['locationvaue']) ? '' : $params['locationvaue'];
        $this->is_bill = empty($params['bill']) ? 0 : $params['bill'];
        $this->is_repair = empty($params['repair']) ? 0 : $params['repair'];
        $this->is_on = empty($params['putaway']) ? 0 : $params['putaway'];
        $this->created_at = time();
        if ($this->save()) {
            // 保存规格

            // 保存其他图片
            $arr = [];
            if (!empty($params['goodsOtherImg'])) {
                foreach ($params['goodsOtherImg'] as $key => $value) {
                    $arr[] = [$value, $this->id, 3];
                }
                

            }
            if ($this->desc) {
                if ($this->desc == 1) {
                    $arr[] = [$params['describeText'], $this->id, 1];
                }
                if ($this->desc == 2) {
                    foreach ($params['destImg'] as $key => $value) {
                        $arr[] = [$value, $this->id, 2];
                    }
                }
            }
            if (!empty($arr)) {
                $result = Yii::$app->db
                ->createCommand()
                ->batchInsert(GoodsOthersModel::tableName(), 
                    ['value', 'g_id', 'type'], $arr)
                ->execute();
            }

            // 保存属性值
            if (!empty($params['attrs'])) {
                $arr = [];
                foreach ($params['attrs'] as $key => $value) {
                    if (empty($value)) {
                        continue;
                    }
                    $arr[] = [$key, $value, $this->id];
                }
                if (!empty($arr)) {
                    $result = Yii::$app->db
                    ->createCommand()
                    ->batchInsert(GoodsAttributesModel::tableName(), 
                        ['a_id', 'avalue', 'g_id'], $arr)
                    ->execute();
                }
            }
            return true;
        }
        var_dump($this->errors);exit;
        return false;
    }
}
