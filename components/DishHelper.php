<?php
/**
 * Extends Dish with new functions
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

use \app\models\Dish;
use \app\models\Course;

class DishHelper
{
    const TYPE_FIRST_COURSE = 0;
    const TYPE_SECOND_COURSE = 1;
    const TYPE_DESSERT = 2;
    const TYPE_DRINK = 3;

    /**
     * Retrieves all the dishes of a certain type
     *
     * @param int $type The type of dish
     *
     * @return array an Array of \app\models\Dish
     */
    public function getDishesOfType($type)
    {
        return Dish::find()
            ->joinWith('courses')
            ->where(['course.type' => $type])
            ->all();
    }

}
