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
 * @property string $name
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
            [['dateStart', 'dateFinish', 'boat'], 'required'],
            [['dateStart', 'dateFinish'], 'safe'],
            [['boat'], 'integer'],
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
            'dateStart' => Yii::t('app', 'Date Start'),
            'dateFinish' => Yii::t('app', 'Date Finish'),
            'boat' => Yii::t('app', 'Boat'),
            'name' => Yii::t('app', 'Name'),
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
