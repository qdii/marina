<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "fraction".
 *
 * @property integer $ingredient
 * @property integer $product
 * @property double $fraction
 *
 * @property Ingredient $ingredient0
 * @property Product $product0
 */
class Fraction extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'fraction';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['ingredient', 'product'], 'integer'],
            [['fraction'], 'number']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'ingredient' => Yii::t('app', 'Ingredient'),
            'product' => Yii::t('app', 'Product'),
            'fraction' => Yii::t('app', 'Fraction'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getIngredient0()
    {
        return $this->hasOne(Ingredient::className(), ['id' => 'ingredient']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProduct0()
    {
        return $this->hasOne(Product::className(), ['id' => 'product']);
    }
}
