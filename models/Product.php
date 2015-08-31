<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "product".
 *
 * @property integer $id
 * @property string $name
 * @property integer $vendor
 * @property integer $unit
 * @property integer $quantity
 * @property double $weight
 *
 * @property Fraction[] $fractions
 * @property Unit $unit0
 * @property Vendor $vendor0
 * @property Proportion[] $proportions
 */
class Product extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'product';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'quantity', 'weight'], 'required'],
            [['vendor', 'unit', 'quantity'], 'integer'],
            [['weight'], 'number'],
            [['name'], 'string', 'max' => 255]
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
            'vendor' => Yii::t('app', 'Vendor'),
            'unit' => Yii::t('app', 'Unit'),
            'quantity' => Yii::t('app', 'Quantity'),
            'weight' => Yii::t('app', 'Weight'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getFractions()
    {
        return $this->hasMany(Fraction::className(), ['product' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUnit0()
    {
        return $this->hasOne(Unit::className(), ['id' => 'unit']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getVendor0()
    {
        return $this->hasOne(Vendor::className(), ['id' => 'vendor']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProportions()
    {
        return $this->hasMany(Proportion::className(), ['product' => 'id']);
    }
}
