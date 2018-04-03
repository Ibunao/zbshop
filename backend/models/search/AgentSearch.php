<?php

namespace backend\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\AgentUserModel;

/**
 * AgentSearch represents the model behind the search form of `common\models\AgentUserModel`.
 */
class AgentSearch extends AgentUserModel
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'age', 'created_at', 'updated_at'], 'integer'],
            [['username', 'mobile', 'sex', 'password', 'idcard_img1', 'idcard_img2', 'bankcard', 'bank', 'area', 'status', 'openid', 'unionid'], 'safe'],
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
        $query = AgentUserModel::find();

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
            'age' => $this->age,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'username', $this->username])
            ->andFilterWhere(['like', 'mobile', $this->mobile])
            ->andFilterWhere(['like', 'sex', $this->sex])
            ->andFilterWhere(['like', 'password', $this->password])
            ->andFilterWhere(['like', 'idcard_img1', $this->idcard_img1])
            ->andFilterWhere(['like', 'idcard_img2', $this->idcard_img2])
            ->andFilterWhere(['like', 'bankcard', $this->bankcard])
            ->andFilterWhere(['like', 'bank', $this->bank])
            ->andFilterWhere(['like', 'area', $this->area])
            ->andFilterWhere(['like', 'status', $this->status])
            ->andFilterWhere(['like', 'openid', $this->openid])
            ->andFilterWhere(['like', 'unionid', $this->unionid]);

        return $dataProvider;
    }
}
