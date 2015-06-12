<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "cruise".
 *
 * @property integer $id
 * @property string $dateStart
 * @property string $dateFinish
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
            [['id', 'dateStart', 'dateFinish'], 'required'],
            [['id'], 'integer'],
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
        ];
    }
}
