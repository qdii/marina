<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "unit".
 *
 * @property integer $id
 * @property string $shortName
 * @property string $name
 * @property integer $weight
 *
 * @property Ingredient[] $ingredients
 * @property Product[] $products
 */
class Unit extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'unit';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['shortName', 'name', 'weight'], 'required'],
            [['weight'], 'integer'],
            [['shortName'], 'string', 'max' => 64],
            [['name'], 'string', 'max' => 256]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'shortName' => Yii::t('app', 'Short Name'),
            'name' => Yii::t('app', 'Name'),
            'weight' => Yii::t('app', 'how many grams is one unit'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getIngredients()
    {
        return $this->hasMany(Ingredient::className(), ['unit' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProducts()
    {
        return $this->hasMany(Product::className(), ['unit' => 'id']);
    }
}
