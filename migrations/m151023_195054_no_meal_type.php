<?php

use yii\db\Schema;
use yii\db\Migration;

class m151023_195054_no_meal_type extends Migration
{
    public function up()
    {
        $this->createTable('course', [
            'id' => $this->primaryKey(),
            'type' => $this->integer()->notNull(),
            'meal' => $this->integer()->notNull(),
            'dish' => $this->integer()->notNull()
        ], 'COLLATE = utf8_bin');

        $this->addForeignKey(
            'course_fk_meal',
            'course',
            'meal',
            'meal',
            'id',
            'CASCADE',
            'CASCADE'
        );

        $this->addForeignKey(
            'course_fk_dish',
            'course',
            'dish',
            'dish',
            'id',
            'RESTRICT',
            'CASCADE'
        );

        $meals = \app\models\Meal::find()->all();
        $dishes = [];
        foreach ($meals as $meal) {
            $id = $meal->id;
            $dishes[] = [ 0, $id, $meal->firstCourse];
            $dishes[] = [ 1, $id, $meal->secondCourse];
            $dishes[] = [ 2, $id, $meal->dessert];
            $dishes[] = [ 3, $id, $meal->drink ];
        }
        $this->batchInsert('course', [ 'type', 'meal', 'dish' ], $dishes);
    }

    public function down()
    {
        $this->dropForeignKey('course_fk_dish', 'course');
        $this->dropForeignKey('course_fk_meal', 'course');
        $this->dropTable('course');

        return false;
    }
}
