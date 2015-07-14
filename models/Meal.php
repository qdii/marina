<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "meal".
 *
 * @property integer $id
 * @property integer $nbGuests
 * @property integer $firstCourse
 * @property integer $secondCourse
 * @property integer $dessert
 * @property integer $drink
 * @property integer $cook
 * @property string $date
 * @property string $type
 *
 * @property Dish $firstCourse0
 * @property Dish $secondCourse0
 * @property Dish $dessert0
 * @property Dish $drink0
 * @property User $cook0
 */
class Meal extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'meal';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['nbGuests', 'firstCourse', 'secondCourse', 'dessert', 'drink', 'cook'], 'integer'],
            [['firstCourse', 'secondCourse', 'dessert', 'drink', 'cook', 'date'], 'required'],
            [['date'], 'safe'],
            [['type'], 'string']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'nbGuests' => Yii::t('app', 'Nb Guests'),
            'firstCourse' => Yii::t('app', 'First Course'),
            'secondCourse' => Yii::t('app', 'Second Course'),
            'dessert' => Yii::t('app', 'Dessert'),
            'drink' => Yii::t('app', 'Drink'),
            'cook' => Yii::t('app', 'Cook'),
            'date' => Yii::t('app', 'Date'),
            'type' => Yii::t('app', 'Type'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getFirstCourse0()
    {
        return $this->hasOne(Dish::className(), ['id' => 'firstCourse']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSecondCourse0()
    {
        return $this->hasOne(Dish::className(), ['id' => 'secondCourse']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDessert0()
    {
        return $this->hasOne(Dish::className(), ['id' => 'dessert']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDrink0()
    {
        return $this->hasOne(Dish::className(), ['id' => 'drink']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCook0()
    {
        return $this->hasOne(User::className(), ['id' => 'cook']);
    }
}