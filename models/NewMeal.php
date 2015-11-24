<?php

namespace app\models;

use Yii;
use yii\base\Model;

/**
 * This is the model class to create a new meal from the calendar.
 */
class NewMeal extends Model
{
    public $mealId;
    public $nbGuests;
    public $firstCourse;
    public $secondCourse;
    public $dessert;
    public $drink;
    public $cook;
    public $date;
    public $cruise;

    /**
     * Specify rules to verify data
     *
     * @return array A rule specification
     */
    public function rules()
    {
        return [
            [['nbGuests', 'firstCourse', 'secondCourse', 'dessert',
            'drink', 'cook', 'cruise', 'mealId'], 'integer'],
            [['nbGuests', 'firstCourse', 'secondCourse', 'dessert',
            'drink', 'cook', 'cruise', 'date', 'mealId'], 'required'],
            [['date'], 'safe'],
            [['backgroundColor'], 'string', 'max' => 7]
        ];
    }
}
