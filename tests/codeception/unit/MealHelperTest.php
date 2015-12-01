<?php
/**
 * Tests the class MealHelper
 *
 * PHP version 5.4
 *
 * @category Units
 * @package  Codeception
 * @author   Victor Lavaud (qdii) <victor.lavaud@gmail.com>
 * @license  GPLv2 https://www.gnu.org/licenses/gpl-2.0.html
 * @link     https://github.com/marina
 */

use \app\components\MealHelper;
use \app\models\Meal;

class MealHelperTest extends \yii\codeception\DbTestCase
{
    public function testDuplicateMeal()
    {
        $meal = Meal::find()->one();
        $copy = MealHelper::duplicate($meal);
        $this->assertNotNull($copy);
        $this->assertEquals($meal->cruise,          $copy->cruise);
        $this->assertEquals($meal->backgroundColor, $copy->backgroundColor);
        $this->assertEquals($meal->nbGuests,        $copy->nbGuests);
    }

    public function testDuplicateMealOverride()
    {
        $meal = Meal::find()->one();
        $copy = MealHelper::duplicate($meal, ['nbGuests' => 1212]);
        $this->assertNotNull($copy);
        $this->assertEquals($meal->cruise,          $copy->cruise);
        $this->assertEquals($meal->backgroundColor, $copy->backgroundColor);
        $this->assertEquals(1212,                   $copy->nbGuests);
    }
}
