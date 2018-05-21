<?php

namespace common\models;

use Yii;
use common\models\GoodsOthersModel;
use yii\db\Query;
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
    // 一页多少个
    public $pageSize = 6;
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
            [['c_id', 'g_id', 'name', 'desc', 'created_at'], 'required'],
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
        // $tr = Yii::$app->db->beginTransaction();
        // try {
            $this->c_id = $params['cid'];
            $gids = [];
            foreach ($params['groupsValue'] as $key => $value) {
                if ($value == 'true') {
                    $gids[] = $key;
                }
            }
            $this->g_id = implode(',', $gids);
            $this->name = $params['goodsNameValue'];
            $this->spec = $params['pickedSpec'];
            if ($this->spec == 1) {
                // 多规格的
            }else{
                // 单规格的
                $this->wx_price = empty($params['specSingle']['goodsWxPrice']) ? 0 : $params['specSingle']['goodsWxPrice'];
                $this->market_price = empty($params['specSingle']['goodsOriPrice']) ? 0 : $params['specSingle']['goodsOriPrice'];
                $this->stores = empty($params['specSingle']['goodsStore']) ? 0 : $params['specSingle']['goodsStore'];
                $this->barcode = empty($params['specSingle']['goodsNo']) ? '' : $params['specSingle']['goodsNo'];
            }
            $this->image = $params['goodsMasterImgAttr'];
            $this->desc = empty($params['describeAttr']) ? 0 : $params['describeAttr'];
            $this->limit = empty($params['limitCount']) ? 9999999 : $params['limitCount'];
            $this->location = empty($params['location']) ? '' : $params['location'];
            $this->is_bill = empty($params['bill']) ? 0 : $params['bill'];
            $this->is_repair = empty($params['repair']) ? 0 : $params['repair'];
            $this->is_on = empty($params['putaway']) ? 0 : $params['putaway'];
            $this->created_at = time();
            if ($this->save()) {
                // 保存多规格
                // 如果是1则表示多规格
                $spec = [];
                
                if ($params['pickedSpec'] == 1) {
                    /**
                     * 多规格要求价格等几个输入框必须填写，不然插入sql报错
                     * @var [type]
                     */
                    foreach ($params['specSend'] as $key => $item) {
                        // 规格组合部分
                        $specArr = array_slice($item, 0, -5);
                        $specName = [];
                        $sId = [];
                        $svId = [];
                        foreach ($specArr as $key => $value) {
                            $specName[] = $value['name'];
                            $sId[] = $value['sid'];
                            $svId[] = $value['svid'];
                        }
                        // 剩余部分
                        $img = $price = $goodsOriPrice = $goodsStore = $goodsNo = '';
                        $specArr = array_slice($item, -5);
                        foreach ($specArr as $key => $item) {
                            if (isset($item['img'])) {
                                $img = $item['img'];
                            } elseif (isset($item['price'])) {
                                $price = $item['price'];
                            } elseif (isset($item['goodsOriPrice'])) {
                                $goodsOriPrice = $item['goodsOriPrice'];
                            } elseif (isset($item['goodsStore'])) {
                                $goodsStore = $item['goodsStore'];
                            } elseif (isset($item['goodsNo'])) {
                                $goodsNo = $item['goodsNo'];
                            }
                        }
                        $spec[] = ['sids' => implode(',', $sId), 's_v_ids' => implode(',', $svId), 's_v_value' => implode(',', $specName), 'g_id' => $this->id, 'image' => $img, 'price' => $price, 'store' => $goodsStore, 'barcode' => $goodsNo];
                    }
                    $result = Yii::$app->db     //选择使用的数据库
                        ->createCommand()
                        ->batchInsert('shop_goods_specifications',     //选择使用的表 
                            ['sids', 's_v_ids', 's_v_value', 'g_id', 'image', 'price', 'store', 'barcode'],$spec)
                        ->execute();
                    if (!$result) {
                        var_dump('保存错误');
                    }
                }
                // 保存其他图片
                $arr = [];
                if (!empty($params['goodsOtherImgAttrs'])) {
                    foreach ($params['goodsOtherImgAttrs'] as $key => $value) {
                        $arr[] = [$value, $this->id, 3];
                    }
                    

                }
                if ($this->desc) {
                    if ($this->desc == 1) {
                        $arr[] = [$params['describeCont'], $this->id, 1];
                    }
                    if ($this->desc == 2) {
                        foreach ($params['describeCont'] as $key => $value) {
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
                if (!empty($params['attrsValue'])) {
                    $arr = [];
                    foreach ($params['attrsValue'] as $key => $value) {
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
            // $tr->commit();
        // } catch (Exception $e) {
        //     //回滚
        //     $tr->rollBack();
        //     echo  "rollback";
        // }
        var_dump($this->errors);exit;
        return false;
    }
    public function getGoods($page, $arr = [], $order = [])
    {
        $model = self::find()->select(['id', 'wx_price', 'image', 'name'])
            ->andWhere(['is_on' => 1]);
        if ($arr) {
            $model = $model->where($arr);
        }
        if (empty($order)) {
            $model = $model->orderBy(['created_at' => SORT_DESC,]);
        }else{
            $model = $model->orderBy($order);
        }
        $model->limit($this->pageSize)->offset(($page-1)*$this->pageSize);
        $result = $model->asArray()
            ->all();
        if (empty($result)) {
            $result = [];
        }
        return $result;
    }
    public function getGoodsInfo($gid)
    {
        $resp = [];
        // 可选择的规格
        $specCheck = [];
        $result = self::find()->select(['*'])
            ->where(['id' => $gid])
            ->asArray()
            ->one();
        // 多规格
        if ($result && $result['spec'] == 1) {
            $res1 = (new Query)->from('shop_goods_specifications')
                ->where(['g_id' => $result['id']])
                ->all();

            foreach ($res1 as $key => $item) {
                $spec = explode(',', $item['snames']);
                $specValues = explode(',', $item['s_v_value']);
                $specIds = explode(',', $item['s_v_ids']);
                foreach ($spec as $key => $value) {
                    $specCheck[$value][$specIds[$key]] = $specValues[$key];
                }
                $result['stores'] += $item['store'];
                $result['specImg'][] = $item['image'];
            }
        }
        // 其他图片
        $otherImg = (new Query)->select(['value'])->from('shop_goods_others')
            ->where(['g_id' => $gid])
            ->column();
        $resp['otherImg'] = $otherImg;
        $resp['info'] = $result;
        $result['specCheck'] = $specCheck;
        if (isset($res1)) {
            $resp['spec'] = $res1;
        }
        $attri = [];
        // 属性  
        $attriTemp = (new Query)->select(['ga.avalue', 'a.name'])->from('shop_attributes a')
            ->leftJoin('shop_goods_attributes ga', 'ga.a_id = a.id')
            ->where(['ga.g_id' => $gid])
            ->all();
        foreach ($attriTemp as $key => $value) {
            $attri[$value['name']] = $value['avalue']; 
        }
        $resp['attri'] = $attri;
        // 详情图片/文字描述
        $detailImg = (new Query)->select('value')->from('shop_goods_others')
            ->where(['g_id' => $gid])
            ->andWhere(['type' => $result['desc']])
            ->column();
        $resp['detailImg'] = $detailImg;
        return $resp;
    }
}