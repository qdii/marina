<?php
/**
 * Helps with meal stuff
 *
 * PHP version 5.4
 *
 * @category Components
 * @package  Components
 * @author   Victor Lavaud (qdii) <victor.lavaud@gmail.com>
 * @license  GPLv2 https://www.gnu.org/licenses/gpl-2.0.html
 * @link     https://github.com/marina
 *
 */
namespace app\components;

use \app\models\Meal;

class MealHelper
{
    /**
     * Clones a Meal
     *
     * @param Meal  $original         The original object to clone.
     * @param array $forcedAttributes An [ attribute => value ] array.
     *
     * @return Meal The new meal
     */
    public function duplicate(Meal $original, $forcedAttributes=[])
    {
        $newMeal = new Meal;
        $attributes = ['nbGuests', 'cook', 'date', 'backgroundColor', 'cruise'];
        foreach($attributes as $attr) {
            $val = $original->$attr;
            if (array_key_exists($attr, $forcedAttributes)) {
                $val = $forcedAttributes[$attr];
            }
            $newMeal->$attr = $val;
        }
        return $newMeal;
    }
}
