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
            $courses = Course::findAll(['dish' => $dish->id]);
            foreach ($courses as $course) {
                $this->assertEquals($type, $course->type);
            }
        }
    }
}
