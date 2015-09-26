<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "auth".
 *
 * @property integer $id
 * @property integer $user
 * @property string $src
 * @property integer $srcid
 *
 * @property User $user0
 */
class Auth extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'auth';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user', 'src', 'srcid'], 'required'],
            [['user', 'srcid'], 'integer'],
            [['src'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'user' => Yii::t('app', 'User'),
            'src' => Yii::t('app', 'Src'),
            'srcid' => Yii::t('app', 'Srcid'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser0()
    {
        return $this->hasOne(User::className(), ['id' => 'user']);
    }
}
