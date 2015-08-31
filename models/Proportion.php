<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "proportion".
 *
 * @property integer $ingredient
 * @property integer $product
 * @property double $weight
 *
 * @property Ingredient $ingredient0
 * @property Product $product0
 */
class Proportion extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'proportion';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['ingredient', 'product', 'weight'], 'required'],
            [['ingredient', 'product'], 'integer'],
            [['weight'], 'number']
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
            'weight' => Yii::t('app', 'Weight'),
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
