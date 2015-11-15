<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "course".
 *
 * @property integer $id
 * @property integer $type
 * @property integer $meal
 * @property integer $dish
 *
 * @property Dish $dish0
 * @property Meal $meal0
 */
class Course extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'course';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['type', 'meal', 'dish'], 'required'],
            [['type', 'meal', 'dish'], 'integer']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'type' => Yii::t('app', 'Type'),
            'meal' => Yii::t('app', 'Meal'),
            'dish' => Yii::t('app', 'Dish'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDish0()
    {
        return $this->hasOne(Dish::className(), ['id' => 'dish']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMeal0()
    {
        return $this->hasOne(Meal::className(), ['id' => 'meal']);
    }
}
