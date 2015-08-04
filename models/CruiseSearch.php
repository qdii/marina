<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Cruise;

/**
 * CruiseSearch represents the model behind the search form about `app\models\Cruise`.
 */
class CruiseSearch extends Cruise
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'boat'], 'integer'],
            [['dateStart', 'dateFinish'], 'safe'],
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
        $query = Cruise::find();

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
            'dateStart' => $this->dateStart,
            'dateFinish' => $this->dateFinish,
            'boat' => $this->boat,
        ]);

        return $dataProvider;
    }
}
