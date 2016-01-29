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
use \app\models\Product;
use \app\models\Proportion;
use \app\models\Fraction;
use \app\models\Composition;
use \app\models\Ingredient;
use \app\models\Dish;
use \app\models\Cruise;
use \app\models\Meal;
use \app\models\Boat;
use yii\helpers\ArrayHelper;

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
        $products = [];
        $fractions = Fraction::findAll([ 'ingredient' => $ingred ]);
        foreach ($fractions as $fraction) {
            $product = Product::findOne([
                'id'     => $fraction->product,
                'vendor' => $vendor,
            ]);
            $products[] = $product;
        }
        return $products;
    }

    /**
     * Returns the products together with the quantities associated to
     * the given ingredient.
     *
     * @param int $ingred The id of the ingredient to find a product for
     * @param int $qty    The amount of ingredient in grams
     * @param int $vendor The id of the vendor where the product will be sought 
     *
     * @return array [ 'id' => 'qty' ] where id is a product id, and quantity
     *         is a quantity of product in grams
     */
    public function selectProducts($ingred, $qty, $vendor)
    {
        assert(is_int($ingred));
        assert(is_float($qty));
        assert(is_int($vendor));

        $products = $this->pickProduct($ingred, $vendor);
        $list = [];
        $portions = $this->_getPortions(
            $ingred,
            ArrayHelper::getColumn($products, 'id')
        );
        foreach ( $products as $product ) {
            $portion    = $this->_getProportionAndFraction($ingred, $product->id, $portions);
            $proportion = $portion['proportion'];
            $fraction   = $portion['fraction'];

            $qtyProduct = $qty * $fraction / $proportion;
            $list[$product->id] = $qtyProduct;
        }

        return $list;
    }

    /**
     * Returns the fraction and the proportion from a portion list
     *
     * @param int   $ingred   The id of an ingredient
     * @param int   $product  The id of a product
     * @param array $portions The portion list
     *
     * @return The fraction and proportion of the couple (ingredient, product)
     */
    private function _getProportionAndFraction($ingred, $product, $portions)
    {
        $ret = [];
        foreach ($portions as $portion) {
            if ($portion['ingredient'] != $ingred) {
                continue;
            }

            if ($portion['product'] != $product) {
                continue;
            }

            $proportion = $portion['weight'] === null ? 0 : $portion['weight'];
            $fraction   = $portion['fraction'] === null ? 1 : $portion['fraction'];

            $ret['proportion'] = $proportion;
            $ret['fraction']   = $fraction;
        }

        return $ret;
    }

    /**
     * Return the proportions and fractions for different ingredient/products
     *
     * @param int   $ingred   The id of an ingredient
     * @param array $products An array of product ids
     *
     * @return array [ 'ingredient', 'product', 'proportion', 'fraction' ];
     */
    private function _getPortions($ingred, $products)
    {
        assert(is_int($ingred));
        assert(is_array($products));
        $portions = [];
        foreach ($products as $prod) {
            $cond = [
                'ingredient' => $ingred,
                'product'    => $prod
            ];

            $fraction   = Fraction::findOne($cond)->fraction;
            $proportion = Proportion::findOne($cond)->weight;

            $portions[] = [
                'ingredient' => $ingred,
                'product'    => $prod,
                'weight'     => $proportion,
                'fraction'   => $fraction
            ];
        }

        return $portions;
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
     * Returns a squeezed version of the dictionary
     * It cumulates the values of the different 'names'
     *
     * @param array $dict A list of ['id' => id, 'qty' => value, 'name' => name]
     *
     * @return array A list of ['name' => value] where there can be at most
     * one instance of 'name'
     */
    private function _squeezeDictionary($dict)
    {
        $squeezedDict = [];
        foreach ($dict as $couple) {
            $id   = $couple['id'];
            $qty  = $couple['qty'];
            $name = $couple['name'];

            if (!array_key_exists($id, $dict)) {
                $squeezedDict[$id] = [
                    'qty' => 0,
                    'name' => $name
                ];
            }

            $squeezedDict[$id]['qty'] += $qty;
        }

        $res = [];
        foreach ($squeezedDict as $id => $entry) {
            $res[] = [
                'id'   => $id,
                'qty'  => $entry['qty'],
                'name' => $entry['name']
            ];
        }

        return $res;
    }

    /**
     * Computes a shopping list from an ingredient list based on the
     * products offered by a given vendor
     *
     * @param array $ingrList A ['id' => ingredient, 'qty' => quantity] list
     * @param int   $vendor   A vendor id
     *
     * @return array An array [ product_id => foo ], where foo is an
     * array containing a 'name' and a 'qty'.
     *
     */
    public function getShoppingListFromIngredientList($ingrList, $vendor)
    {
        $productList = [];

        // sums duplicate ingredients quantity
        $ingrList = $this->_squeezeDictionary($ingrList);

        $ingredientList = [];
        foreach ( $ingrList as $item ) {
            $ingrId   = $item['id'];
            $ingrQty  = $item['qty'];
            $ingrName = $item['name'];

            $products = $this->pickProduct($ingrId, $vendor);
            if (empty($products)) {
                $ingredientList[$ingrId]['qty']  = $ingrQty;
                $ingredientList[$ingrId]['name'] = $ingrName;
                continue;
            }

            foreach ( $products as $product ) {
                $prodId  = $product->id;
                if (!isset($productList[$prodId])) {
                    $productList[$prodId]['name'] = $product->name;
                    $productList[$prodId]['qty']  = 0;
                }
                $prodQty = $this->getAmountOfProduct(
                    $ingrId,
                    $prodId,
                    $ingrQty,
                    null
                );
                $productList[$prodId]['qty'] += $prodQty;
            }
        }

        return ['products' => $productList, 'ingredients' => $ingredientList];
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
