<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Auth;

/**
 * AuthSearch represents the model behind the search form about `app\models\Auth`.
 */
class AuthSearch extends Auth
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'user', 'srcid'], 'integer'],
            [['src'], 'safe'],
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
        $query = Auth::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'user' => $this->user,
            'srcid' => $this->srcid,
        ]);

        $query->andFilterWhere(['like', 'src', $this->src]);

        return $dataProvider;
    }
}
