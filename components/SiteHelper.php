<?php

namespace app\components;

use \app\models\Cruise;

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
            $newMeal = new \app\models\Meal;

            $newMeal->nbGuests        = $meal->nbGuests;
            $newMeal->firstCourse     = $meal->firstCourse;
            $newMeal->secondCourse    = $meal->secondCourse;
            $newMeal->dessert         = $meal->dessert;
            $newMeal->drink           = $meal->drink;
            $newMeal->cook            = $meal->cook;
            $newMeal->date            = $meal->date;
            $newMeal->backgroundColor = $meal->backgroundColor;
            $newMeal->cruise          = $newCruise->id;

            if (!$newMeal->save()) {
                throw new \Exception("Cannot create new meal");
            }
        }
        $transaction->commit();

        return $newCruise;
    }
}
