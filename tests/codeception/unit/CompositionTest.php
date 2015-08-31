<?php

use \app\SiteController;
use \app\components\CompositionHelper;
use \app\models\Composition;

/**
 * Unit test for the class Price Compute
 */

class SiteControllerTest extends \Codeception\TestCase\Test
{
    public function fixtures()
    {
        return [
            'compositions' => CompositionFixture::className()
        ];
    }

    public function testUpdateComposition()
    {
        $helper = new CompositionHelper;

        $ncompositions = count($this->compositions);
        $oldValue = Composition::findOne(['dish'=>1,'ingredient'=>19336])->quantity;
        $newValue = $oldValue + 2.0;
        $updated = $helper->updateDelete(1, 19336, $newValue);

        // update should work as the composition exists
        $this->assertTrue($updated);

        $this->assertNotEquals($oldValue, $newValue);

        // an update of an existing composition should not change the number of items
        $this->assertEquals(
            $ncompositions,
            Composition::findAll()->count()
        );

        // now delete it (i.e. set its quantity to 0)
        $deleted  = $helper->updateDelete(1, 19336, 0);

        // deleting an existing element should work
        $this->assertTrue($deleted);

        // deleting a composition should leave us with one less row
        $this->assertEquals(
            $ncompositions - 1,
            Composition::findAll()->count()
        );

        $this->assertNull(Composition::findOne(['dish'=>1,'ingredient'=>19336]));
    }

    /**
     * Tests that the correct information is returned
     * by the CompositionHelper component for water
     *
     * @return void
     */
    public function testGetInformationWater()
    {
        $helper = new CompositionHelper;

        $waterWeight = 500.0;

        // 20 is the id of a dish containing only water
        $info = $helper->getInformation(20);

        foreach( $info as $row ) {
            if (isset($row['total_qty'])) {
                $this->assertEquals($waterWeight, $row['total_qty'], '', 0.01);
                $this->assertEquals(0.0, $row['total_cal'], '', 0.01);
                $this->assertEquals(0.0, $row['total_prot'], '', 0.01);
            } else {
                $this->assertEquals($waterWeight, $row['quantity'], '', 0.01);
                $this->assertEquals(0.0, $row['energy_kcal'], '', 0.01);
                $this->assertEquals(0.0, $row['protein'], '', 0.01);
            }
        }
    }

    /**
     * Tests that the correct information is returned
     * by the CompositionHelper component for cereals and milk
     */
    public function testGetInformationCerealsMilk()
    {
        $helper = new CompositionHelper;

        $cerealsWeight   = 75.0;
        $cerealsProtUnit = 9.660;
        $cerealsCalUnit  = 412.0;
        $cerealsProt     = $cerealsWeight * $cerealsProtUnit / 100.0;
        $cerealsCal      = $cerealsWeight * $cerealsCalUnit / 100.0;

        $milkWeight   = 250.0;
        $milkProtUnit = 3.3;
        $milkCalUnit  = 50.0;
        $milkProt     = $milkWeight * $milkProtUnit / 100.0;
        $milkCal      = $milkWeight * $milkCalUnit / 100.0;

        $totalWeight = $milkWeight  + $cerealsWeight;
        $totalCal    = $cerealsCal  + $milkCal;
        $totalProt   = $cerealsProt + $milkProt;

        $info = $helper->getInformation(15);

        foreach ( $info as $row ) {
            if (isset($row['total_qty'])) {
                $this->assertEquals($totalWeight, $row['total_qty'], '', 0.01);
                $this->assertEquals($totalCal, $row['total_cal'], '', 0.01);
                $this->assertEquals($totalProt, $row['total_prot'], '', 0.01);
            } else if ($row['id'] == 1174) {
                $this->assertEquals($milkWeight, $row['quantity'], '', 0.01);
                $this->assertEquals($milkCal, $row['energy_kcal'], '', 0.01);
                $this->assertEquals($milkProt, $row['protein'], '', 0.01);
            } else {
                $this->assertEquals($cerealsWeight, $row['quantity'], '', 0.01);
                $this->assertEquals($cerealsCal, $row['energy_kcal'], '', 0.01);
                $this->assertEquals($cerealsProt, $row['protein'], '', 0.01);
            }
        }
    }

    /**
     * Checks that the cloning function of the composition helper works
     *
     * @return void
     */
    public function testCloneDish()
    {
        $helper = new CompositionHelper;

        $ncompositions = Composition::findAll()->count();
        $ndish         = Dish::findAll()->count();

        $dish = new \app\models\Dish;
        $dish->name = "whatever";
        $dish->type = "firstCourse";
        $dish->insert();

        $this->assertEquals($ndish + 1, Dish::findAll()->count());

        $cloned = $helper->cloneDish(9, $dish->id);
        $this->assertTrue($cloned);

        // 5 lines should be inserted (because there were 5 entries corresponding
        // to dish 9 in the table "composition"
        $this->assertEquals(
            $ncompositions + 5,
            Composition::findAll()->count()
        );
    }
}
