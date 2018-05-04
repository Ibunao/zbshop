<?php

namespace app\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\GoodsModel;

/**
 * GoodsSearch represents the model behind the search form of `common\models\GoodsModel`.
 */
class GoodsSearch extends GoodsModel
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'c_id', 'stores', 'limit', 'created_at', 'updated_at'], 'integer'],
            [['g_id', 'name', 'spec', 'barcode', 'image', 'desc', 'location', 'is_bill', 'is_repair', 'is_on'], 'safe'],
            [['wx_price', 'market_price'], 'number'],
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
        $query = GoodsModel::find();

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
            'c_id' => $this->c_id,
            'wx_price' => $this->wx_price,
            'market_price' => $this->market_price,
            'stores' => $this->stores,
            'limit' => $this->limit,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'g_id', $this->g_id])
            ->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'spec', $this->spec])
            ->andFilterWhere(['like', 'barcode', $this->barcode])
            ->andFilterWhere(['like', 'image', $this->image])
            ->andFilterWhere(['like', 'desc', $this->desc])
            ->andFilterWhere(['like', 'location', $this->location])
            ->andFilterWhere(['like', 'is_bill', $this->is_bill])
            ->andFilterWhere(['like', 'is_repair', $this->is_repair])
            ->andFilterWhere(['like', 'is_on', $this->is_on]);

        return $dataProvider;
    }
}
