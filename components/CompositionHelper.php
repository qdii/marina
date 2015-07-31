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
use \yii\helpers\ArrayHelper;

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

    /**
     * Inserts in the database a copy of all the composition of a given dish
     *
     * @param integer $oldDishId The dish to clone
     * @param integer $newDishId The lines to insert
     *
     * @return true if the compositions were successfully inserted
     */
    function cloneDish($oldDishId, $newDishId)
    {
        $srcCompos = Composition::findAll(['dish' => $oldDishId]);
        foreach ($srcCompos as $compo) {
            $compo->dish = $newDishId;
            assert($compo->validate());
        }

        $rows = ArrayHelper::getColumn($srcCompos, 'attributes');

        $compoModel = new Composition;

        $nrows = \Yii::$app->db
            ->createCommand()
            ->batchInsert(Composition::tableName(), $compoModel->attributes(), $rows)
            ->execute();

        return $nrows == count($srcCompos);

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
}
