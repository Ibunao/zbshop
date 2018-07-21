<?php

namespace backend\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\GoodsSpecificationsModel;
use common\models\GoodsModel;
/**
 * GoodsSpecificationsSearch represents the model behind the search form of `common\models\GoodsSpecificationsModel`.
 */
class GoodsSpecificationsSearch extends GoodsSpecificationsModel
{

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'g_id', 'barcode', 'store'], 'integer'],
            [['sids', 'snames', 's_v_value', 's_v_ids', 'image', 'disabled'], 'safe'],
            [['price'], 'number'],
            ['goods_name', 'safe']
        ];
    }
    
    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = GoodsSpecificationsModel::find();
        $query->joinWith('goods');
        $query->select("shop_goods_specifications.*, shop_goods.name goods_name");
        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'g_id' => $this->g_id,
            'price' => $this->price,
            'barcode' => $this->barcode,
            'store' => $this->store,
            'shop_goods.spec' => 1
        ]);

        $query->andFilterWhere(['like', 'sids', $this->sids])
            ->andFilterWhere(['like', 'snames', $this->snames])
            ->andFilterWhere(['like', 's_v_value', $this->s_v_value])
            ->andFilterWhere(['like', 's_v_ids', $this->s_v_ids])
            ->andFilterWhere(['like', 'image', $this->image])
            ->andFilterWhere(['like', 'disabled', $this->disabled]);
        $query->andFilterWhere(['like', 'shop_goods.name', $this->goods_name]);
        $query->orderBy('id DESC');
        return $dataProvider;
    }
}
