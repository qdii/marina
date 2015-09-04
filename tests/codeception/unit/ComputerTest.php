<?php

use \app\components\PriceComputer;

/**
 * Unit test for the class Price Compute
 */

class PriceComputerTest extends \Codeception\TestCase\Test
{
    /**
     * Checks if the energy is computed correctly
     *
     * @return void
     */
    public function testEnergy()
    {
        // the object we want to test
        $computer = new PriceComputer(
            \app\models\Ingredient::find()->all(),
            \app\models\Composition::find()->all(),
            \app\models\Unit::find()->all(),
            \app\models\Dish::find()->all(),
            \app\models\Meal::find()->all()
        );

        // take a meal that has only ONE dish: tea and coffee
        $meal = \app\models\Meal::findOne(['id' => 57]);
        $this->assertNotNull($meal);

        $dishes = $computer->getDishesFromMeals([ $meal ]);

        foreach ( $dishes as $dish ) {
            $compositions[] = $dish->getCompositions();
        }

        $values = $computer->getIntakesOfMeals(
            [ $meal ],
            [ 'energy_kcal', 'protein' ]
        );

        // the total energy is the sum of the energy in the coffee ...
        $quantityCoffee = 5.0;
        $energyCoffee = 353.0; // per 100g
        $expectedEnergyCoffee = $quantityCoffee * $energyCoffee / 100.0;

        // .. and in the tea
        $quantityTea = 5.0;
        $energyTea = 1.0;
        $expectedEnergyTea = $quantityTea * $energyTea / 100.0;

        $totalEnergy = $expectedEnergyTea + $expectedEnergyCoffee;

        $this->assertEquals($totalEnergy, $values['energy_kcal']);
    }

    /**
     * Test if ingredients are missing from the list
     *
     * @return void
     */
    public function testNoMissingIngredients()
    {
        // the object we want to test
        $computer = new PriceComputer(
            \app\models\Ingredient::find()->all(),
            \app\models\Composition::find()->all(),
            \app\models\Unit::find()->all(),
            \app\models\Dish::find()->all(),
            \app\models\Meal::find()->all()
        );

        // take a meal that has only ONE dish: tea and coffee
        $meal = \app\models\Meal::findOne(['id' => 57]);

        $this->assertNotNull($meal);

        $computer->addMeal($meal);

        $items = $computer->items;

        // there should be two ingredients : tea and coffee
        $this->assertEquals(2, count($items));
    }
}
