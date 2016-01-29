<?php
/**
 * Extends Composition basic functions
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

use \app\models\Boat;
use \app\models\Composition;
use \app\models\Cruise;
use \app\models\Dish;
use \app\models\DishType;
use \app\models\Fraction;
use \app\models\Ingredient;
use \app\models\Meal;
use \app\models\Product;
use \app\models\Proportion;
use \yii\helpers\ArrayHelper;
use \app\components\CruiseHelper;
/**
 * Provides new functions around Composition
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
class CompositionHelper
{
    // cache ['id' => 'products'];
    private $_productsById;
    private $_cruisesById;
    private $_mealsById;
    private $_dishesById;
    private $_proportions;
    private $_fractions;
    private $_compositions;

    public function _getProduct($id)
    {
        if (array_key_exists($id, $this->_productsById)) {
            return $this->_productsById[$id];
        }
        return Product::findOne($id);
    }

    public function __construct(
        $products    = [],
        $cruises     = [],
        $meals       = [],
        $dishes      = [],
        $proportions = [],
        $fractions   = [],
        $compositions= []
    ) {
        if (empty($products)) { $products = Product::find()->all(); }
        if (empty($cruises))  { $cruises  = Cruise::find()->all();  }
        if (empty($meals))    { $meals    = Meal::find()->all();    }
        if (empty($dishes))   { $dishes   = Dish::find()->all();    }
        if (empty($proportions)) { $proportions = Proportion::find()->all(); }
        if (empty($fractions))   { $fractions   = Fraction::find()->all(); }
        if (empty($compositions)) { $compositions = Composition::find()->all(); }

        $this->_productsById = ArrayHelper::index($products, 'id');
        $this->_cruisesById  = ArrayHelper::index($cruises, 'id');
        $this->_mealsById    = ArrayHelper::index($meals, 'id');
        $this->_dishesById   = ArrayHelper::index($dishes, 'id');
        $this->_proportions  = $proportions;
        $this->_fractions    = $fractions;
        $this->_compositions = $compositions;
    }

    /**
     * Updates or delete a composition. Does not create one if it does not exist
     *
     * @param integer $dishId       The id of an existing dish
     * @param integer $ingredientId The id of an existing ingredient
     * @param float   $quantity     How much of the igredient should
     * be in the dish. 0 to delete
     *
     * @return true if the composition was succesfully updated or deleted
     */
    function updateDelete($dishId, $ingredientId, $quantity)
    {
        $item = Composition::findOne(
            [
                'dish'       => $dishId,
                'ingredient' => $ingredientId,
            ]
        );

        if ($item == null) {
            return false;
        }

        if ($quantity == 0) {
            $item->delete();
        } else {
            $item->quantity = $quantity;
            $item->save();
        }

        return true;
    }

    /**
     * Returns informations about a certain dish
     *
     * @param integer $dishId The id of an existing dish
     *
     * @return array Information about that dish
     */
    public function getInformation($dishId)
    {
        $query = new \yii\db\Query;
        $query->select(
            [
                'composition.quantity',
                'ingredient.name',
                'ingredient.id',
                '(composition.quantity * ingredient.energy_kcal) / 100.0 as energy_kcal',
                '(composition.quantity * ingredient.protein / 100.0) as protein',
            ]
        )
            ->from('composition')
            ->join(
                'left join',
                'ingredient',
                'composition.ingredient = ingredient.id'
            )
            ->where(['dish' => $dishId])
            ->addOrderBy(['ingredient.name' => SORT_DESC]);

        $totals = new \yii\db\Query;
        $totals->select(
            [
                'SUM(composition.quantity) as total_qty',
                'SUM(composition.quantity * ingredient.energy_kcal) / 100.0 as total_cal',
                'SUM(composition.quantity * ingredient.protein) / 100.0 as total_prot',
            ]
        )
            ->from('composition')
            ->join(
                'left join',
                'ingredient',
                'composition.ingredient = ingredient.id'
            )
            ->where(['dish' => $dishId]);


        return array_merge($query->all(), $totals->all());
    }

    public function getCookbook(\app\models\Cruise $cruise, $vendor, $nbGuests)
    {
        $cookbook = [];
        $cruiseHelper = new CruiseHelper;
        $cruiseId = $cruise->id;

        foreach ($cruiseHelper->getDishesFromCruise($cruiseId) as $dish) {
            // Is the dish already present in the cookbook?
            $found = false;
            foreach ( $cookbook as &$recipe ) {
                if ($recipe['name'] == $dish->name) {
                    $found = true;
                    $recipe['count']++;
                }
            }

            if ($found)
                continue;

            $items = $this->_getRecipeItemsForDish($dish, $vendor, $nbGuests);

            $cookbook[] = [
                'name'  => $dish->name,
                'items' => $items,
                'count' => 1
            ];
        }

        return $cookbook;
    }

    private function _getCompositionFromDish($dishId)
    {
        $compositions = [];
        foreach($this->_compositions as $composition) {
            if ($composition->dish != $dishId) {
                continue;
            }
            $compositions[] = $composition;
        }
        return $compositions;
    }

    private function _getRecipeItemsForDish($dish, $vendor, $nbGuests)
    {
        $helper = new ProductPicker;

        $compos = $this->_getCompositionFromDish($dish->id);

        $list = [];
        foreach ($compos as $compo) {
            $products = $helper->selectProducts(
                $compo->ingredient,
                floatval($compo->quantity) * $nbGuests,
                $vendor->id
            );
            foreach ($products as $id => $qty) {
                $product = $this->_getProduct($id);
                $list[] = [
                    'id'       => $id,
                    'exactQty' => $qty,
                    'qty'      => ceil($qty),
                    'name'     => $product->name
                ];
            }
        }
        return $list;
    }
}
