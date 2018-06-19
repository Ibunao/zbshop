<?php

namespace backend\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\OrderModel;

/**
 * OrdersSearch represents the model behind the search form of `common\models\OrdersModel`.
 */
class OrderSearch extends OrderModel
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'order_id', 'integrals', 'status', 'ship_status', 'create_at', 'update_at'], 'integer'],
            [['openid', 'pay_id', 'ship_no', 'ship_type', 'remark', 'name', 'mobile', 'address'], 'safe'],
            [['price', 'express_fee', 'deduction', 'pay_price'], 'number'],
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
        $query = OrderModel::find();

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
            'order_id' => $this->order_id,
            'price' => $this->price,
            'express_fee' => $this->express_fee,
            'integrals' => $this->integrals,
            'deduction' => $this->deduction,
            'pay_price' => $this->pay_price,
            'status' => $this->status,
            'ship_status' => $this->ship_status,
            'create_at' => $this->create_at,
            'update_at' => $this->update_at,
        ]);

        $query->andFilterWhere(['like', 'openid', $this->openid])
            ->andFilterWhere(['like', 'pay_id', $this->pay_id])
            ->andFilterWhere(['like', 'ship_no', $this->ship_no])
            ->andFilterWhere(['like', 'ship_type', $this->ship_type])
            ->andFilterWhere(['like', 'remark', $this->remark])
            ->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'mobile', $this->mobile])
            ->andFilterWhere(['like', 'address', $this->address]);
        $query->orderBy('create_at DESC');

        return $dataProvider;
    }
}
