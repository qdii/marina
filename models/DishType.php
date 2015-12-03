<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "dish_type".
 *
 * @property integer $dish
 * @property integer $type
 *
 * @property Dish $dish0
 */
class DishType extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'dish_type';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['dish'], 'required'],
            [['dish', 'type'], 'integer']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'dish' => Yii::t('app', 'Dish'),
            'type' => Yii::t('app', 'Type'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDish0()
    {
        return $this->hasOne(Dish::className(), ['id' => 'dish']);
    }
}
