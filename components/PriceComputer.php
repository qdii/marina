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
use \app\models\Composition;
use \app\models\Ingredient;
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
 *
 */
class PriceComputer
{
    public $items     = [];

    private $_unitsNameById = [];
    private $_unitsById = [];

    private $_ingredients;
    private $_compositions;
    private $_dishes;
    private $_meals;

    private $_dishesById;
    private $_ingredientsById;

    /**
     * Constructs a PriceComputer
     *
     * @param array $ingredients  An array of \app\models\Ingredient to compute from
     * @param array $compositions An array of \app\models\Composition to compute from
     * @param array $units        An array of \app\models\Unit to compute from
     * @param array $dishes       An array of \app\models\Dish to compute from
     * @param array $meals        An array of \app\models\Meal to compute from
     */
    public function __construct($ingredients, $compositions, $units, $dishes, $meals)
    {
        $this->_unitsNameById = ArrayHelper::map($units, 'id', 'display');
        $this->_ingredients   = $ingredients;
        $this->_compositions  = $compositions;
        $this->_dishes        = $dishes;
        $this->_meals         = $meals;

        $this->_dishesById      = ArrayHelper::index($dishes, 'id');
        $this->_ingredientsById = ArrayHelper::index($ingredients, 'id');
        $this->_unitsById       = ArrayHelper::index($units, 'id');
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
        foreach ( $meals as $meal ) {
            $dishes[] = $this->_dishesById[ $meal->firstCourse  ];
            $dishes[] = $this->_dishesById[ $meal->secondCourse ];
            $dishes[] = $this->_dishesById[ $meal->dessert      ];
            $dishes[] = $this->_dishesById[ $meal->drink        ];
        }

        return $dishes;
    }

    public function addMeal(\app\models\Meal $meal)
    {
        $nbGuests = $meal->nbGuests;

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

        $this->items[$id]['quantity']     = 0;
        $this->items[$id]['unitQuantity'] = 0;
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

        foreach ( $this->_compositions as $item ) {
            if (!in_array($item->dish, $dishIds)) {
                continue;
            }

            $ingredientId = $item->ingredient;
            $ingredient   = $this->_ingredientsById[$ingredientId];
            $quantity     = $item->quantity * $nbGuests;

            $this->_initItem($ingredientId);

            $quantity += $this->items[$ingredient->id]['quantity'];

            $unit = $ingredient->unit ?
                $this->_unitsById[$ingredient->unit] : null;

            $unitName = $unit ?
                $this->_unitsNameById[$ingredient->unit] : "";

            $quantityInGrams = $unit ?
                $quantity * $unit->weight : $quantity;

            $price = $ingredient->price * $quantityInGrams;
            $name  = $ingredient->name;

            $this->items[$ingredientId]['name']         = $name;
            $this->items[$ingredientId]['quantity']     = $quantityInGrams;
            $this->items[$ingredientId]['price']        = $price;
            $this->items[$ingredientId]['unitPrice']    = $ingredient->price;
            $this->items[$ingredientId]['unit']         = $ingredient->unit;
            $this->items[$ingredientId]['unitName']     = $unitName;
            $this->items[$ingredientId]['unitQuantity'] = $quantity;
            $this->items[$ingredientId]['id']           = $ingredientId;
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

        foreach ( $this->_compositions as $item ) {
            if (!in_array($item->dish, $dishIds)) {
                continue;
            }

            $quantity   = $item->quantity; // in grams
            $ingredient = $this->_ingredientsById[$item->ingredient];

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
