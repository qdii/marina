<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "dish".
 *
 * @property integer $id
 * @property string $name
 * @property string $type
 *
 * @property Composition[] $compositions
 * @property Ingredient[] $ingredients
 * @property Course[] $courses
 * @property Meal[] $meals
 * @property Meal[] $meals0
 * @property Meal[] $meals1
 * @property Meal[] $meals2
 */
class Dish extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'dish';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['type'], 'string'],
            [['name'], 'string', 'max' => 256]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'name' => Yii::t('app', 'Name'),
            'type' => Yii::t('app', 'Type'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCompositions()
    {
        return $this->hasMany(Composition::className(), ['dish' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getIngredients()
    {
        return $this->hasMany(Ingredient::className(), ['id' => 'ingredient'])->viaTable('composition', ['dish' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCourses()
    {
        return $this->hasMany(Course::className(), ['dish' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMeals()
    {
        return $this->hasMany(Meal::className(), ['firstCourse' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMeals0()
    {
        return $this->hasMany(Meal::className(), ['secondCourse' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMeals1()
    {
        return $this->hasMany(Meal::className(), ['dessert' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMeals2()
    {
        return $this->hasMany(Meal::className(), ['drink' => 'id']);
    }
}
