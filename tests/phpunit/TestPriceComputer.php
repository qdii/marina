<?php

require_once __DIR__ . '/../../vendor/autoload.php';
require_once __DIR__ . '/../../vendor/yiisoft/yii2/Yii.php';

use \app\components\PriceComputer;

/**
 * Unit test for the class Price Compute
 */

/**
 * Disables foreign key checks temporarily.
 *
 * @return void
 */
class TruncateOperation extends \PHPUnit_Extensions_Database_Operation_Truncate
{
    public function execute(
        \PHPUnit_Extensions_Database_DB_IDatabaseConnection $connection,
        \PHPUnit_Extensions_Database_DataSet_IDataSet $dataSet
    ) {
        $connection->getConnection()->query("SET foreign_key_checks = 0");
        parent::execute($connection, $dataSet);
        $connection->getConnection()->query("SET foreign_key_checks = 1");
    }
}

class PriceComputerTest extends PHPUnit_Extensions_Database_TestCase
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

    /**
     * Establishes a connection to the database
     *
     * @return mixed A mysql connection
     */
    public function getConnection()
    {
        $db   = include __DIR__ . '/../../config/db.php';
        $pdo  = new PDO($db['dsn'], $db['username'], $db['password']);
        $conn = $this->createDefaultDBConnection($pdo, ':memory:');

        return $conn;
    }

    /**
     * Returns an objectified database
     *
     * @return DataSet A dataset of the base
     */
    public function getDataSet()
    {
        return $this->createMySQLXMLDataSet( __DIR__ . '/dataset.xml');
    }


    protected function getSetUpOperation() {
        /* If you want cascading truncates, false otherwise.
         * If unsure choose false. */
        $cascadeTruncates = true;

        return new \PHPUnit_Extensions_Database_Operation_Composite(array(
            new TruncateOperation($cascadeTruncates),
            \PHPUnit_Extensions_Database_Operation_Factory::INSERT()
        ));
    }
}
