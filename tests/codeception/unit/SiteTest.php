<?php
/**
 * Unit test for the class SiteController
 */
use \app\models\Cruise;
use \app\models\Meal;

class SiteTest extends \yii\codeception\DbTestCase
{
    public function testDuplicateCruise()
    {
        // get any cruise
        $oldCruise = Cruise::find()->one();
        $this->assertNotNull($oldCruise);

        $nMealsOldCruise = Meal::find()->where(['cruise' => $oldCruise->id])->count();

        $siteHelper = new \app\components\SiteHelper;

        $newCruise = $siteHelper->duplicateCruise($oldCruise);
        $this->assertTrue(Cruise::find(['id' => $newCruise->id])->exists());

        $nMealsNewCruise = Meal::find()->where(['cruise' => $newCruise->id])->count();

        // the new cruise should have as many meals as the old cruise
        $this->assertEquals($nMealsOldCruise, $nMealsNewCruise);

        // check that every parameter is the same but the id
        $this->assertNotEquals($oldCruise->id, $newCruise->id);
        $this->assertEquals($oldCruise->dateStart,  $newCruise->dateStart);
        $this->assertEquals($oldCruise->dateFinish, $newCruise->dateFinish);
        $this->assertEquals($oldCruise->boat,       $newCruise->boat);
    }
}
