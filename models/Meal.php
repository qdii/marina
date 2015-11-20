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
 * @property integer $cruise
 * @property string $backgroundColor
 *
 * @property Course[] $courses
 * @property Dish $firstCourse0
 * @property Dish $secondCourse0
 * @property Dish $dessert0
 * @property Dish $drink0
 * @property User $cook0
 * @property Cruise $cruise0
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
            [['nbGuests', 'firstCourse', 'secondCourse', 'dessert', 'drink', 'cook', 'cruise'], 'integer'],
            [['firstCourse', 'secondCourse', 'dessert', 'drink', 'cook', 'date', 'cruise'], 'required'],
            [['date'], 'safe'],
            [['backgroundColor'], 'string', 'max' => 7]
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
            'cruise' => Yii::t('app', 'Cruise'),
            'backgroundColor' => Yii::t('app', 'Background Color'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCourses()
    {
        return $this->hasMany(Course::className(), ['meal' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getFirstCourse0()
    {
        return $this->getCourses()->where(['type' => 0])->one()->getDish0();
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSecondCourse0()
    {
        return $this->getCourses()->where(['type' => 1])->one()->getDish0();
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDessert0()
    {
        return $this->getCourses()->where(['type' => 2])->one()->getDish0();
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDrink0()
    {
        return $this->getCourses()->where(['type' => 3])->one()->getDish0();
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCook0()
    {
        return $this->hasOne(User::className(), ['id' => 'cook']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCruise0()
    {
        return $this->hasOne(Cruise::className(), ['id' => 'cruise']);
    }
}
