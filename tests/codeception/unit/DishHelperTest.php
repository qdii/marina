<?php
/**
 * Tests the class DishHelper
 *
 * PHP version 5.4
 *
 * @category Units
 * @package  Codeception
 * @author   Victor Lavaud (qdii) <victor.lavaud@gmail.com>
 * @license  GPLv2 https://www.gnu.org/licenses/gpl-2.0.html
 * @link     https://github.com/marina
 */

use \app\components\DishHelper;
use \app\models\Composition;
use \app\models\Dish;
use \app\models\DishType;
use \app\models\Course;
use \yii\helpers\ArrayHelper;

class DishHelperTest extends \yii\codeception\DbTestCase
{
    public function testGetDishesOfType()
    {
        $type = 0;
        $dishHelper = new DishHelper;
        $dishes = $dishHelper->getDishesOfType($type);
        $this->assertNotEmpty($dishes);
        foreach ($dishes as $dish) {
            $dishId = $dish->id;
            $dishTypes = DishType::findAll(['dish' => $dishId]);
            $hasRightType = false;
            foreach ($dishTypes as $dishType) {
                $hasRightType |= ($dishType->type == $type);
            }
            $this->assertEquals(1, $hasRightType);
        }
    }

    /**
     * Checks that the cloning function of the composition helper works
     *
     * @return void
     */
    public function testCloneDish()
    {
        $helper = new DishHelper;

        $ncompositions = Composition::find()->count();
        $ndish         = Dish::find()->count();

        // Cloning dish of id "9", i.e. all composition will be reproduced.
        $dish = $helper->cloneDish(9);

        $this->assertNotNull($dish);
        $this->assertEquals($ndish + 1, Dish::find()->count());

        // 5 lines should be inserted (because there were 5 entries corresponding
        // to dish 9 in the table "composition"
        $this->assertEquals(
            $ncompositions + 5,
            Composition::find()->count()
        );

        $oldDishTypes = DishType::findAll(['dish' => 9]);
        $newDishTypes = DishType::findAll(['dish' => $dish->id]);

        $types    = ArrayHelper::getColumn($oldDishTypes, 'type');
        $newTypes = ArrayHelper::getColumn($newDishTypes, 'type');

        $this->assertEquals($newTypes, $types);
    }
}
