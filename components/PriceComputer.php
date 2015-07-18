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
 *
 */
namespace app\components;
use \yii\helpers\ArrayHelper;

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
 *
 */
class PriceComputer
{
    public $ingredients     = [];

    public function addCompositionItem(\app\models\Composition $item, $nbGuests)
    {
        assert( $nbGuests != 0 );

        $ingredient = $item->getIngredient0()->one();
        $quantity   = $item->quantity * $nbGuests;

        if ( !isset( $this->ingredients[$ingredient->id]['quantity'] ) )
            $this->ingredients[$ingredient->id]['quantity'] = 0;
        else
            $quantity += $this->ingredients[$ingredient->id]['quantity'];

        $this->ingredients[$ingredient->id]['name']       = $ingredient->name;
        $this->ingredients[$ingredient->id]['quantity']   = $quantity;
        $this->ingredients[$ingredient->id]['price']      = $ingredient->price * $quantity;
        $this->ingredients[$ingredient->id]['unitPrice']  = $ingredient->price;
        $this->ingredients[$ingredient->id]['unitName']   = $ingredient->getUnit0()->one()->shortName;
    }

    public function addDish(\app\models\Dish $dish, $nbGuests)
    {
        foreach ( $dish->getCompositions()->all() as $item ) {
            $this->addCompositionItem($item, $nbGuests);
        }
    }

    /**
     * Return the dishes contained in this meal
     *
     * @param \app\models\Meal $meal The meal to consider
     *
     * @return array The dishes associated to this meal
     */
    private function _getDishesFromMeal(\app\models\Meal $meal)
    {
        return \app\models\Dish::findAll(
            [
                'id' => [
                    $meal->firstCourse,
                    $meal->secondCourse,
                    $meal->dessert,
                    $meal->drink
                ]
            ]
        );
    }

    /**
     * Return the dishes contained in these meals
     *
     * @param array $meals The meals to consider
     *
     * @return array The dishes associated to these meals
     */
    private function _getDishesFromMeals($meals)
    {
        $dishIds = [];
        foreach ( $meals as $meal ) {
            $dishIds[] = $meal->firstCourse;
            $dishIds[] = $meal->secondCourse;
            $dishIds[] = $meal->dessert;
            $dishIds[] = $meal->drink;
        }

        return \app\models\Dish::findAll([ 'id' => $dishIds ]);
    }
    public function addMeal(\app\models\Meal $meal)
    {
        $nbGuests = $meal->nbGuests;
        foreach ( $this->_getDishesFromMeal($meal) as $dish ) {
            $this->addDish($dish, $nbGuests);
        }
    }

    public function addMeals( $meals )
    {
        array_walk( $meals, function( $m ) { $this->addMeal( $m ); } );
    }

    public function price()
    {
        return array_sum(
            array_map(
                function ($ingredient) {
                    return $ingredient['price'];
                },
                $this->ingredients
            )
        );
    }

    /**
     * Returns the number of calories, sucrose, etc. for an
     * ingredient in a dish
     *
     * @param \app\models\Composition $item     The component to consider
     * @param string                  $property 'protein', 'energy_kcal', etc.
     *
     * @return The number of that property in grams (like, 10g of proteins)
     */
    private function _getIntake(\app\models\Composition $item, $property)
    {
        assert(count($property) != 0);

        $quantity   = $item->quantity; // in grams
        $ingredient = $item->getIngredient0()->one();

        // number of calories (or whatever) per 100g
        $nominalValue = $ingredient->$property;

        return $quantity * $nominalValue / 100;
    }

    /**
     * Returns the number of calories, sucrose, etc. for a set of dish
     *
     * @param array  $dishes   The dishes to consider
     * @param string $property 'protein', 'energy_kcal', etc.
     *
     * @return The number of that property in grams (like, 10g of proteins)
     */
    private function _getIntakeOfDishes($dishes, $property)
    {
        $intakes       = [];
        $dishIds       = ArrayHelper::getColumn($dishes, 'id');
        $compositions  = \app\models\Composition::findAll(['dish' => $dishIds]);

        $ingredientIds = ArrayHelper::getColumn($compositions, 'ingredient');

        // retrieve all ingredients in one shot
        $ingredients   = \app\models\Ingredient::findAll(['id' => $ingredientIds]);

        $ingredientById = ArrayHelper::index($ingredients, 'id');

        foreach ( $compositions as $item ) {
            $quantity   = $item->quantity; // in grams
            $ingredient = $ingredientById[$item->ingredient];

            // number of calories (or whatever) per 100g
            $nominalValue = $ingredient->$property;

            $intakes[] = $quantity * $nominalValue / 100;
        }
        return array_sum($intakes);
    }

    /**
     * Returns the number of calories, sucrose, etc. for a set of meals
     *
     * @param array  $meals    The set of meals to consider
     * @param string $property 'protein', 'energy_kcal', etc.
     *
     * @return The number of that property in grams (like, 10g of proteins)
     */
    public function getIntakeOfMeals($meals, $property)
    {
        $dishes = $this->_getDishesFromMeals($meals);
        return $this->_getIntakeOfDishes($dishes, $property);
    }

}
?>
