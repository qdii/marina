<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "ingredient".
 *
 * @property integer $id
 * @property string $name
 * @property string $price
 * @property integer $duration
 * @property integer $unit
 * @property string $sucrose
 * @property string $glucose
 * @property string $fructose
 * @property string $water
 * @property string $energy_kcal
 * @property string $energy_kj
 * @property string $protein
 * @property string $lipid
 * @property string $fat
 * @property string $ash
 * @property string $carbohydrates
 * @property string $sugars
 * @property string $fiber
 *
 * @property Composition[] $compositions
 * @property Dish[] $dishes
 * @property Unit $unit0
 */
class Ingredient extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'ingredient';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'price', 'duration'], 'required'],
            [['price', 'sucrose', 'glucose', 'fructose', 'water', 'energy_kcal', 'energy_kj', 'protein', 'lipid', 'fat', 'ash', 'carbohydrates', 'sugars', 'fiber'], 'number'],
            [['duration', 'unit'], 'integer'],
            [['name'], 'string', 'max' => 128]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'name' => Yii::t('app', 'Name'),
            'price' => Yii::t('app', 'Price'),
            'duration' => Yii::t('app', 'nb of days before expiration'),
            'unit' => Yii::t('app', 'Unit'),
            'sucrose' => Yii::t('app', 'sucrose in g per 100g'),
            'glucose' => Yii::t('app', 'glucose in g per 100g'),
            'fructose' => Yii::t('app', 'fructose in g per 100g'),
            'water' => Yii::t('app', 'water in g per 100g'),
            'energy_kcal' => Yii::t('app', 'energy in kcal per 100g'),
            'energy_kj' => Yii::t('app', 'energy in kj per 100g'),
            'protein' => Yii::t('app', 'protein in g per 100g'),
            'lipid' => Yii::t('app', 'lipid in g per 100g'),
            'fat' => Yii::t('app', 'total lipid in g per 100g'),
            'ash' => Yii::t('app', 'ash in g per 100g'),
            'carbohydrates' => Yii::t('app', 'carbohydrates in g per 100g'),
            'sugars' => Yii::t('app', 'total sugars in g per 100g'),
            'fiber' => Yii::t('app', 'fiber total dietary in g per 100g'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCompositions()
    {
        return $this->hasMany(Composition::className(), ['ingredient' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDishes()
    {
        return $this->hasMany(Dish::className(), ['id' => 'dish'])->viaTable('composition', ['ingredient' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUnit0()
    {
        return $this->hasOne(Unit::className(), ['id' => 'unit']);
    }
}
