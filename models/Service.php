<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "service".
 *
 * @property integer $cook
 * @property string $date
 * @property string $type
 * @property integer $meal
 *
 * @property User $cook0
 */
class Service extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'service';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['cook', 'date', 'type'], 'required'],
            [['cook', 'meal'], 'integer'],
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
            'cook' => 'Cook',
            'date' => 'Date',
            'type' => 'Type',
            'meal' => 'Meal',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCook0()
    {
        return $this->hasOne(User::className(), ['id' => 'cook']);
    }
}
