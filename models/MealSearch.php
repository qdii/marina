<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Meal;

/**
 * MealSearch represents the model behind the search form about `app\models\Meal`.
 */
class MealSearch extends Meal
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'nbGuests', 'firstCourse', 'secondCourse', 'dessert', 'drink', 'cook', 'cruise'], 'integer'],
            [['date', 'backgroundColor'], 'safe'],
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
        $query = Meal::find();

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
            'nbGuests' => $this->nbGuests,
            'firstCourse' => $this->firstCourse,
            'secondCourse' => $this->secondCourse,
            'dessert' => $this->dessert,
            'drink' => $this->drink,
            'cook' => $this->cook,
            'date' => $this->date,
            'cruise' => $this->cruise,
        ]);

        $query->andFilterWhere(['like', 'backgroundColor', $this->backgroundColor]);

        return $dataProvider;
    }
}
