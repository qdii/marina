<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Fraction;

/**
 * FractionSearch represents the model behind the search form about `app\models\Fraction`.
 */
class FractionSearch extends Fraction
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['ingredient', 'product'], 'integer'],
            [['fraction'], 'number'],
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
        $query = Fraction::find();

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
            'ingredient' => $this->ingredient,
            'product' => $this->product,
            'fraction' => $this->fraction,
        ]);

        return $dataProvider;
    }
}
