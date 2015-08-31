<?php

use \app\components\ProductPicker;

class ProductPickerTest extends \Codeception\TestCase\Test
{
    public function testOneProduct()
    {
        $ingredient = 13317; // beef patties
        $vendor     = 1;     // Tesco
        $picker = new ProductPicker;

        $product = $picker->pickProduct($ingredient, $vendor);
        $this->assertNotEmpty($product);
    }

    public function testManyProducts()
    {
        $ingredient = 19297;
        $vendor     = 1;
        $picker     = new ProductPicker;

        $products   = $picker->pickProduct($ingredient, $vendor);
        $this->assertCount(6, $products);
    }
}
