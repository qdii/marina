<?php

use yii\db\Schema;
use yii\db\Migration;

use app\components\DishHelper;
use app\models\Dish;
use app\models\DishType;

class m151211_120712_drop_type_column extends Migration
{
    public function up()
    {
        $this->dropColumn('dish', 'type');
    }


    public function down()
    {
        $this->addColumn('dish', 'type', 'string not null');

        $this->_createTypeForColumn(DishHelper::TYPE_FIRST_COURSE,  'firstCourse');
        $this->_createTypeForColumn(DishHelper::TYPE_SECOND_COURSE, 'secondCourse');
        $this->_createTypeForColumn(DishHelper::TYPE_DESSERT,       'dessert');
        $this->_createTypeForColumn(DishHelper::TYPE_DRINK,         'drink');

        return true;
    }

    private function _createTypeForColumn($type, $columnName)
    {
        $dishTypes = DishType::findAll(['type' => $type]);
        foreach($dishTypes as $dishType) {
            $dish = Dish::findOne(['id' => $dishType->dish]);
            if ($dish) {
                $dish->type = $columnName;
                $dish->save();
            }
        }
    }
}
