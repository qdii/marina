<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "cruise".
 *
 * @property integer $id
 * @property string $dateStart
 * @property string $dateFinish
 * @property integer $boat
 *
 * @property Boat $boat0
 * @property Meal[] $meals
 */
class Cruise extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'cruise';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'dateStart', 'dateFinish', 'boat'], 'required'],
            [['id', 'boat'], 'integer'],
            [['dateStart', 'dateFinish'], 'safe']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'dateStart' => Yii::t('app', 'Date Start'),
            'dateFinish' => Yii::t('app', 'Date Finish'),
            'boat' => Yii::t('app', 'Boat'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBoat0()
    {
        return $this->hasOne(Boat::className(), ['id' => 'boat']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMeals()
    {
        return $this->hasMany(Meal::className(), ['cruise' => 'id']);
    }
}
