<?php

use app\components\DishHelper;
use app\models\Dish;
use app\models\DishType;
use yii\db\Schema;
use yii\db\Migration;

class m151211_115354_create_dish_type_entries extends Migration
{
    /**
     * Populates the table dish_type with entries.
     *
     * @param string $name The name of the type of the dish, like 'firstCourse'
     * @param int    $type The type field value in the dish_type table
     */
    private function _createDishesForType($name, $type)
    {
        $dishes = Dish::findAll(['type' => $name]);
        foreach ($dishes as $dish) {
            $dishType = new DishType;
            $dishType->dish = $dish->id;
            $dishType->type = $type;
            $dishType->save();
        }
    }

    public function up()
    {
        $this->_createDishesForType('firstCourse',  DishHelper::TYPE_FIRST_COURSE);
        $this->_createDishesForType('secondCourse', DishHelper::TYPE_SECOND_COURSE);
        $this->_createDishesForType('dessert',      DishHelper::TYPE_DESSERT);
        $this->_createDishesForType('drink',        DishHelper::TYPE_DRINK);
    }

    public function down()
    {
        $dishTypes = DishType::find()->all();
        foreach ($dishTypes as $dishType) {
            if (Dish::findOne(['id' => $dishType->dish])) {
                $dishType->delete();
            }
        }

        return true;
    }
}
