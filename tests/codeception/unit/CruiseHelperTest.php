<?php
/**
 * Tests the class CruiseHelper
 *
 * PHP version 5.4
 *
 * @category Units
 * @package  Codeception
 * @author   Victor Lavaud (qdii) <victor.lavaud@gmail.com>
 * @license  GPLv2 https://www.gnu.org/licenses/gpl-2.0.html
 * @link     https://github.com/marina
 */

use \app\components\CruiseHelper;

class CruiseHelperTest extends \yii\codeception\DbTestCase
{
    public function testGetDishesFromCruise()
    {
        $cruiseId = 99998;
        $dishes = CruiseHelper::getDishesFromCruise($cruiseId);
        $this->assertNotEmpty($dishes);
    }

    public function testGetDishesFromCruiseOfType()
    {
        $cruiseId = 99998;
        $dishes = CruiseHelper::getDishesFromCruiseOfType($cruiseId, 1);
        $this->assertNotEmpty($dishes);
    }
}
