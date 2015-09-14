<?php

use \app\components\ProductPicker;

class ProductPickerTest extends \yii\codeception\DbTestCase
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

    public function testAmountOfOneProduct()
    {
        $ingredient = 1212; // milk
        $product    = 95;   // tin can of milk
        $picker     = new ProductPicker;

        $packs      = $picker->getAmountOfProduct(
            $ingredient,
            $product,
            2000, /* we need 2 kg of milk */
            null
        );

        // ok so a tin can has 340g of milk inside,
        // so we need at least 6 packs
        $this->assertEquals(6.0, $packs, 0.1);
    }

    public function testAmountOfNonMatchingProduct()
    {
        $ingredient = 2006; // spices
        $product    = 95;   // tin can of milk
        $picker     = new ProductPicker;

        $packs      = $picker->getAmountOfProduct(
            $ingredient,
            $product,
            2000, /* we need 2 kg of milk */
            null
        );

        // we can't obtain spcies from cans of milk, so the result should be 0
        $this->assertEquals(0, $packs);
    }

    public function testFractionedProduct()
    {
        $picker = new ProductPicker;

        // ingredient
        $tomatoes = 11529;

        // products
        $vineTomatoes     = 23; // on the vine
        $everydayTomatoes = 75; // normal ones

        $qty = 2000; // we want 2kg of tomatoes

        // seulement 25% du produit doit être composé de vine tomatoes,
        // 25% de 2000g sont 500g, et chaque pack de vine tomatoes en contient
        // 70g (selon la table proportion), donc il nous faut 8 packs.
        $vinePacks = $picker->getAmountOfProduct(
            $tomatoes,
            $vineTomatoes,
            $qty,
            null
        );
        $this->assertEquals(8, $vinePacks);

        // 75% du produit doit être composé de tomates classiques, i.e. 1500g,
        // et chaque pack fait 400g (cf table proportion), donc il nous
        // 4 packs (1600g)
        $everydayPacks = $picker->getAmountOfProduct(
            $tomatoes,
            $everydayTomatoes,
            $qty,
            null
        );
        $this->assertEquals(4, $everydayPacks);
    }

    public function testSelectProducts()
    {
        $picker = new ProductPicker;

        // ingredient
        $tomatoes = 11529;
        $qty      = 2000.0;

        // products
        $vineTomatoes     = 23; // on the vine
        $everydayTomatoes = 75; // normal

        // vendor
        $tesco = 1;

        $list = $picker->selectProducts($tomatoes, $qty, $tesco);
        $this->assertNotEmpty($list);
        $this->assertArrayHasKey($vineTomatoes, $list);
        $this->assertArrayHasKey($everydayTomatoes, $list);
        $this->assertEquals(0.75 * $qty / 400.0, $list[$everydayTomatoes]);
        $this->assertEquals(0.25 * $qty / 70.0, $list[$vineTomatoes]);
    }
}
