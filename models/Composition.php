<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "composition".
 *
 * @property integer $dish
 * @property integer $ingredient
 * @property string $quantity
 *
 * @property Dish $dish0
 * @property Ingredient $ingredient0
 */
class Composition extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'composition';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['dish', 'ingredient'], 'required'],
            [['dish', 'ingredient'], 'integer'],
            [['quantity'], 'number']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'dish' => Yii::t('app', 'Dish'),
            'ingredient' => Yii::t('app', 'Ingredient'),
            'quantity' => Yii::t('app', 'Quantity'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDish0()
    {
        return $this->hasOne(Dish::className(), ['id' => 'dish']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getIngredient0()
    {
        return $this->hasOne(Ingredient::className(), ['id' => 'ingredient']);
    }
}
