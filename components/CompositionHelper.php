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

use \app\models\Composition;

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
}
