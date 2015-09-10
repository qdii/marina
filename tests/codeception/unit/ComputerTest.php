<?php

use \yii\helpers\ArrayHelper;
use \app\components\PriceComputer;
use \app\tests\codeception\fixtures\CompositionFixture;
use \app\tests\codeception\fixtures\DishFixture;
use \app\tests\codeception\fixtures\MealFixture;
use \app\tests\codeception\fixtures\IngredientFixture;
use \app\tests\codeception\fixtures\UnitFixture;

/**
 * Unit test for the class Price Compute
 */

class PriceComputerTest extends \yii\codeception\DbTestCase
{
    public function fixtures()
    {
        return [
            'compositions' => CompositionFixture::className(),
            'ingredients'  => IngredientFixture::className(),
            'dishes'       => DishFixture::className(),
            'units'        => UnitFixture::className(),
            'meals'        => MealFixture::className(),
        ];
    }
}
