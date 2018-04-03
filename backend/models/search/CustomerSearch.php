<?php

namespace backend\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\CustomerModel;
use common\models\AgentUserModel;

/**
 * CustomerSearch represents the model behind the search form of `common\models\CustomerModel`.
 */
class CustomerSearch extends CustomerModel
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'share_id', 'created_at', 'updated_at'], 'integer'],
            [['username', 'mobile', 'password', 'openid', 'unionid'], 'safe'],
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
        $query = CustomerModel::find();

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
            'share_id' => $this->share_id,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'username', $this->username])
            ->andFilterWhere(['like', 'mobile', $this->mobile])
            ->andFilterWhere(['like', 'password', $this->password])
            ->andFilterWhere(['like', 'openid', $this->openid])
            ->andFilterWhere(['like', 'unionid', $this->unionid]);

        return $dataProvider;
    }

}
