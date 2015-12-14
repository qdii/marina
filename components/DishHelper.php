<?php
/**
 * Extends Dish with new functions
 *
 * PHP version 5.4
 *
 * @category Components
 * @package  Components
 * @author   Victor Lavaud (qdii) <victor.lavaud@gmail.com>
 * @license  GPLv2 https://www.gnu.org/licenses/gpl-2.0.html
 * @link     https://github.com/marina
 */
namespace app\components;

use \app\models\Composition;
use \app\models\Course;
use \app\models\Dish;
use \app\models\DishType;
use \yii\helpers\ArrayHelper;

class DishHelper
{
    const TYPE_FIRST_COURSE = 0;
    const TYPE_SECOND_COURSE = 1;
    const TYPE_DESSERT = 2;
    const TYPE_DRINK = 3;

    /**
     * Retrieves all the dishes of a certain type
     *
     * @param int $type The type of dish
     *
     * @return array an Array of \app\models\Dish
     */
    public function getDishesOfType($type)
    {
        return Dish::find()
            ->joinWith('dishTypes')
            ->where(['dish_type.type' => $type])
            ->all();
    }

    /**
     * Duplicates a dish.
     *
     * @param int    dishId The id of the dish to clone.
     * @param string name   The name of the copy of the dish.
     *
     * @return \app\models\Dish The newly created dish, or null if the id is invalid.
     */
    public function cloneDish($dishId, $name)
    {
        $transaction = \Yii::$app->db->beginTransaction();
        $compositionAttributes = (new Composition)->attributes();

        $oldDish = Dish::findOne(['id' => $dishId]);
        if (!$oldDish) {
            return null;
        }

        // Create a new Dish object.
        $newDish       = new Dish;
        $newDish->name = $name;
        $newDish->save();

        $newDishId = $newDish->id;

        // Copy all the ingredients of the first dish.
        $srcCompos = Composition::findAll(['dish' => $dishId]);

        // For each composition, modify the dish id and ...
        foreach ($srcCompos as $compo) {
            $compo->dish = $newDishId;
            assert($compo->validate());
        }

        // ... copy all other attributes
        $rows = ArrayHelper::getColumn($srcCompos, 'attributes');

        \Yii::$app->db
            ->createCommand()
            ->batchInsert(
                Composition::tableName(),
                $compositionAttributes,
                $rows)
            ->execute();

        // Copy all types from the dish.
        $dishTypes = DishType::findAll(['dish' => $dishId]);
        foreach ($dishTypes as $dishType) {
            $newDishType = new DishType;
            $newDishType->type = $dishType->type;
            $newDishType->dish = $newDishId;
            $newDishType->save();
        }

        $transaction->commit();

        return $newDish;
    }

}
