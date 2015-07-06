<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Ingredient;

/**
 * IngredientSearch represents the model behind the search form about `app\models\Ingredient`.
 */
class IngredientSearch extends Ingredient
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'duration', 'unit'], 'integer'],
            [['name'], 'safe'],
            [['price', 'sucrose', 'glucose', 'fructose', 'water', 'energy_kcal', 'energy_kj', 'protein', 'lipid'], 'number'],
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
        $query = Ingredient::find();

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
            'price' => $this->price,
            'duration' => $this->duration,
            'unit' => $this->unit,
            'sucrose' => $this->sucrose,
            'glucose' => $this->glucose,
            'fructose' => $this->fructose,
            'water' => $this->water,
            'energy_kcal' => $this->energy_kcal,
            'energy_kj' => $this->energy_kj,
            'protein' => $this->protein,
            'lipid' => $this->lipid,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name]);

        return $dataProvider;
    }
}
