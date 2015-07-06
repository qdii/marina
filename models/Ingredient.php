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
            [['price', 'sucrose', 'glucose', 'fructose', 'water', 'energy_kcal', 'energy_kj', 'protein', 'lipid'], 'number'],
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
            'duration' => Yii::t('app', 'Duration'),
            'unit' => Yii::t('app', 'Unit'),
            'sucrose' => Yii::t('app', 'Sucrose'),
            'glucose' => Yii::t('app', 'Glucose'),
            'fructose' => Yii::t('app', 'Fructose'),
            'water' => Yii::t('app', 'Water'),
            'energy_kcal' => Yii::t('app', 'Energy Kcal'),
            'energy_kj' => Yii::t('app', 'Energy Kj'),
            'protein' => Yii::t('app', 'Protein'),
            'lipid' => Yii::t('app', 'Lipid'),
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
