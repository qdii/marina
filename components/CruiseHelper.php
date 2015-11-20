<?php
/**
 * Extends Cruise with new functions
 *
 * PHP version 5.4
 *
 * @category Components
 * @package  Components
 * @author   Victor Lavaud (qdii) <victor.lavaud@gmail.com>
 * @license  GPLv2 https://www.gnu.org/licenses/gpl-2.0.html
 * @link     https://github.com/marina
 */
namespace app\components;

use \app\models\Meal;

class CruiseHelper
{
    /**
     * Retrieves all the dishes associated with a cruise
     *
     * @param int $cruiseId an Id of an existing cruise
     *
     * @return array an Array of \app\models\Dish
     */
    public function getDishesFromCruise($cruiseId)
    {
        $dishes = [];
        $meals = Meal::findAll(['cruise' => $cruiseId]);
        foreach ($meals as $meal) {
            $courses = $meal->getCourses()->all();
            foreach ($courses as $course) {
                $dishes[] = $course->getDish0()->one();
            }
        }

        return $dishes;
    }
}
