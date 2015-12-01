<?php

namespace app\components;

use \app\models\Course;
use \app\models\Cruise;
use \app\models\Meal;

class SiteHelper
{
    /**@brief Duplicate a cruise
     * Creates a new cruise with the same dates, name, boat and meals as
     * another cruise.
     *
     * @param Cruise $cruise The cruise to duplicate
     *
     * @return Cruise|null The duplicated cruise
     */
    public function duplicateCruise(Cruise $cruise)
    {
        $newCruise = new Cruise;

        $newCruise->dateStart  = $cruise->dateStart;
        $newCruise->dateFinish = $cruise->dateFinish;
        $newCruise->boat       = $cruise->boat;

        $transaction = $newCruise->getDb()->beginTransaction();
        if (!$newCruise->save()) {
            throw new \Exception("Cannot create new cruise");
        }

        $meals = $cruise->getMeals()->all();
        foreach ( $meals as $meal ) {
            $newMeal = MealHelper::duplicate(
                $meal, ['cruise' => $newCruise->id]);
            if (!$newMeal->save()) {
                throw new \Exception("Cannot create new meal");
            }

            foreach ($meal->getCourses()->all() as $course) {
                $newCourse = new Course;

                $newCourse->meal = $newMeal->id;
                $newCourse->type = $course->type;
                $newCourse->dish = $course->dish;

                if (!$newMeal->save()) {
                    throw new \Exception("Cannot create course");
                }
            }
        }
        $transaction->commit();

        return $newCruise;
    }
}
