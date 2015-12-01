<?php
/**
 * Computes information about meals
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
use \yii\helpers\ArrayHelper;
use \app\models\Composition;
use \app\models\Course;
use \app\models\Ingredient;
use \app\models\Meal;
use \app\models\Unit;

/**
 * Computes the price of a full meal
 *
 * PHP version 5.4
 *
 * @category Components
 * @package  Components
 * @author   Victor Lavaud (qdii) <victor.lavaud@gmail.com>
 * @license  GPLv2 https://www.gnu.org/licenses/gpl-2.0.html
 * @link     https://github.com/marina
 */
class PriceComputer
{
    public $items = [];

    /**
     * Return the dishes contained in this meal
     *
     * @param \app\models\Meal $meal The meal to consider
     *
     * @return array The dishes associated to this meal
     */
    private function _getDishesFromMeal(Meal $meal)
    {
        return $this->getDishesFromMeals([ $meal ]);
    }

    /**
     * Return the dishes contained in these meals
     *
     * @param array $meals The meals to consider
     *
     * @return array The dishes associated to these meals
     */
    public function getDishesFromMeals($meals)
    {
        $dishes = [];
        $mealIds = ArrayHelper::getColumn($meals, 'id');
        $courses = Course::findAll(['meal' => $mealIds]);
        foreach ($courses as $course) {
            $dishes[] = $course->getDish0()->one();
        }

        return $dishes;
    }

    public function addMeal(Meal $meal)
    {
        $nbGuests = $meal['nbGuests'];

        $this->_addDishes($this->_getDishesFromMeal($meal), $nbGuests);
    }

    /**
     * If the item does not exist in the list, sets its quantity and price
     * to 0
     *
     * @param integer $id The id of the ingredient to initialize
     *
     * @return void
     */
    private function _initItem($id)
    {
        if (isset($this->items[$id])) {
            assert(count($this->items[$id]['name']) > 0);
            assert($this->items[$id]['quantity'] > 0);
            return;
        }

        $this->items[$id]['quantity'] = 0;
        $this->items[$id]['weight']   = 0;
    }

    /**
     * Increases the quantity of a given ingredient in the list
     *
     * @param array  $ingredient The id of the ingredient to accumulate
     * @param mixed  $unit       The unit in which the ingredient is shown or null
     * @param float  $qty        How much of the ingredient to add (in $unit)
     *
     * @return void
     */
    private function _accumulateItem($ingredient, $unit, $qty)
    {
        $id = $ingredient['id'];
        assert(isset($this->items[$id]));

        $weight = $unit == null ? $qty : $qty * $unit['weight'];

        $this->items[$id]['ingredient']  = $ingredient;
        $this->items[$id]['name']        = $ingredient['name'];
        $this->items[$id]['unit']        = $unit;
        $this->items[$id]['quantity']   += $qty;
        $this->items[$id]['weight']     += $weight;
    }

    /**
     * Increases the local ingredient variable from a set of dishes
     *
     * @param array   $dishes   The set of dishes to include
     * @param integer $nbGuests How many people will be eating those dishes
     *
     * @return void
     */
    private function _addDishes($dishes, $nbGuests)
    {
        $dishIds = ArrayHelper::getColumn($dishes, 'id');
        $compositions = Composition::find()->where(['dish' => $dishIds])->all();

        foreach ($compositions as $item) {
            $ingredient = $item->getIngredient0()->one();
            $unitId     = $ingredient['unit'];
            $unit       = $unitId != null ? Unit::findOne(['id' => $unitId]) : null;
            $quantity   = $item['quantity'] * $nbGuests;

            $this->_initItem($item['ingredient']);
            $this->_accumulateItem($ingredient, $unit, $quantity);
        }
    }

    public function addMeals( $meals )
    {
        array_walk($meals, function($m) { $this->addMeal($m); });
    }

    public function price()
    {
        return array_sum(
            array_map(
                function ($ingredient) {
                    return $ingredient['price'];
                },
                $this->items
            )
        );
    }

    /**
     * Returns the numbers of calories, sucrose, etc. for a set of dish
     *
     * @param array $dishes The dishes to consider
     * @param array $props  'protein', 'energy_kcal', etc.
     *
     * @return array An array of each property
     */
    private function _getIntakesOfDishes($dishes, $props)
    {
        $intakes       = [];
        $dishIds       = ArrayHelper::getColumn($dishes, 'id');
        $compositions = Composition::find()->where(['dish' => $dishIds])->all();
        foreach ( $props as $property ) {
            $result[$property] = 0;
            $intakes[$property] = [];
        }

        foreach ( $compositions as $item ) {
            $quantity   = $item->quantity; // in grams
            $ingredient = $item->getIngredient0()->one();

            foreach ( $props as $property ) {
                // number of calories (or whatever) per 100g
                $nominalValue = $ingredient->$property;

                $intakes[$property][] = $quantity * $nominalValue / 100;
            }
        }

        $result = [];
        foreach ( $props as $property ) {
            $result[$property] = array_sum($intakes[$property]);
        }

        return $result;
    }

    /**
     * Return the numbers of each of calories, sucrose, etc. for a set of meals
     *
     * @param array $meals The set of meals to consider
     * @param array $props The properties to consider
     *
     * @return array The property values
     */
    public function getIntakesOfMeals($meals, $props)
    {
        $dishes = $this->getDishesFromMeals($meals);
        return $this->_getIntakesOfDishes($dishes, $props);
    }

}
?>
