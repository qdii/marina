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
}
