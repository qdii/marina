<?php
/**
 * Selects products based on a list of ingredients
 *
 * PHP version 5.4
 *
 * @category Components
 * @package  Components
 * @author   Victor Lavaud (qdii) <victor.lavaud@gmail.com>
 * @license  GPLv2 https://www.gnu.org/licenses/gpl-2.0.html
 * @link     https://github.com/marina
 *
 */
namespace app\components;
use app\models\Product;
use app\models\Proportion;
use app\models\Fraction;

/**
 * Selects products based on a list of ingredients
 *
 * PHP version 5.4
 *
 * @category Components
 * @package  Components
 * @author   Victor Lavaud (qdii) <victor.lavaud@gmail.com>
 * @license  GPLv2 https://www.gnu.org/licenses/gpl-2.0.html
 * @link     https://github.com/marina
 *
 */
class ProductPicker
{
    /**
     * Selects the best product for a given ingredient at the given vendor
     *
     * @param int $ingred The id of ingredient to find a product for
     * @param int $vendor The id of vendor of the product
     *
     * @return array an array of products which matches the ingredient.
     */
    public function pickProduct($ingred, $vendor)
    {
        assert(is_int($ingred));
        assert(is_int($vendor));
        $product = Product::find()
            ->rightJoin('fraction', 'fraction.product = product.id')
            ->where(['fraction.ingredient' => $ingred, 'product.vendor' => $vendor]);
        return $product->all();
    }

    /**
     * Computes the amount of product that's necessary to match the given
     * quantity of ingredient
     *
     * @param int   $ingr The ingredient id
     * @param int   $prod The product id
     * @param float $qty  The quantity of ingredient
     * @param int   $unit The unit id of the quantity of ingredient
     *
     * @return float The amount of product necessary
     */
    public function getAmountOfProduct($ingr, $prod, $qty, $unit)
    {
        $couple     = ['ingredient' => $ingr, 'product' => $prod];
        $proportion = Proportion::findOne($couple);
        $fraction   = Fraction::findOne($couple);

        // if the product does not contain any of the given ingredient
        if (!$proportion) {
            return 0;
        }

        // so let's say the product is a pack of veggies from Tesco
        $qtyOfCarrotsInThePack = $proportion->weight;

        // and we need 300g of carrots
        $weNeedThatMuchCarrots = $qty * $fraction->fraction;

        // so this is how many packs we needs
        $howManyPacks = $weNeedThatMuchCarrots / $qtyOfCarrotsInThePack;

        return ceil($howManyPacks);
    }

    /**
     * Converts a given quantity of something in a given unit into the
     * quantity in grams
     *
     * @param float $qty  The quantity of something
     * @param int   $unit The id of an unit
     *
     * @return float The quantity in grams of the converted product
     */
    private function _convertUnitToGrams($qty, $unit)
    {
        // by default, units are expressed in grams
        if (!$unit || $qty == 0) {
            return $qty;
        }
    }
}
