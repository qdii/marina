<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "meal".
 *
 * @property integer $id
 * @property integer $nbGuests
 * @property integer $cook
 * @property string $date
 * @property integer $cruise
 * @property string $backgroundColor
 *
 * @property Course[] $courses
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
            [['nbGuests', 'cook', 'cruise'], 'integer'],
            [['cook', 'date', 'cruise'], 'required'],
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

    public function getCourseByType($type)
    {
        $course = $this->getCourses()->where(['type' => $type])->one();
        if (!$course) {
            return null;
        }

        return $course->getDish0();
    }

    public function getFirstCourse0()
    {
        return $this->getCourseByType(0);
    }

    public function getSecondCourse0()
    {
        return $this->getCourseByType(1);
    }

    public function getDessert0()
    {
        return $this->getCourseByType(2);
    }

    public function getDrink0()
    {
        return $this->getCourseByType(3);
    }
}
