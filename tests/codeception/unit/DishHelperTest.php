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
use \app\models\Dish;
use \app\models\DishType;
use \app\models\Course;

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
}
