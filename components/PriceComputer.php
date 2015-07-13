<?php
namespace app\components;

// computes the price of a full meal
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
    public function getDishesFromMeal(\app\models\Meal $meal)
    {
        return [
            $meal->getFirstCourse0()->one(),
            $meal->getSecondCourse0()->one(),
            $meal->getDessert0()->one(),
            $meal->getDrink0()->one(),
        ];
    }

    public function addMeal(\app\models\Meal $meal)
    {
        $nbGuests = $meal->nbGuests;
        foreach ( $this->getDishesFromMeal($meal) as $dish ) {
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
    public function getIntake(\app\models\Composition $item, $property)
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
    public function getIntakeOfDishes($dishes, $property)
    {
        $intakes = [];
        foreach ( $dishes as $dish ) {
            $intakes[] = $this->getIntakeOfDish($dish, $property);
        }
        return array_sum($intakes);
    }

    /**
     * Returns the number of calories, sucrose, etc. for a given dish
     *
     * @param array  $dish     The dish to consider
     * @param string $property 'protein', 'energy_kcal', etc.
     *
     * @return The number of that property in grams (like, 10g of proteins)
     */
    public function getIntakeOfDish($dish, $property)
    {
        $intakes = [];
        foreach ( $dish->getCompositions()->all() as $item ) {
            $intakes[] = $this->getIntake($item, $property);
        }
        return array_sum($intakes);
    }

    /**
     * Returns the number of calories, sucrose, etc. for a given meal
     *
     * @param array  $meal     The meal to consider
     * @param string $property 'protein', 'energy_kcal', etc.
     *
     * @return The number of that property in grams (like, 10g of proteins)
     */
    public function getIntakeOfMeal($meal, $property)
    {
        $intakes = [];
        foreach ( $this->getDishesFromMeal($meal) as $dish ) {
            $intakes[] = $this->getIntakeOfDish($dish, $property);
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
        $intakes = [];
        foreach ( $meals as $meal ) {
            $intakes[] = $this->getIntakeOfMeal($meal, $property);
        }
        return array_sum($intakes);
    }

}
?>
