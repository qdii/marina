<?php
namespace app\components;

// computes the price of a full meal
class PriceComputer
{
    public $ingredients = [];

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
        foreach( $dish->getCompositions()->all() as $item )
            $this->addCompositionItem($item, $nbGuests);
    }

    public function addMeal(\app\models\Meal $meal)
    {
        $nbGuests = $meal->nbGuests;

        $this->addDish( $meal->getFirstCourse0()    ->one(), $nbGuests );
        $this->addDish( $meal->getSecondCourse0()   ->one(), $nbGuests );
        $this->addDish( $meal->getDessert0()        ->one(), $nbGuests );
        $this->addDish( $meal->getDrink0()          ->one(), $nbGuests );
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
}
?>
