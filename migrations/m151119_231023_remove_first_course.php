<?php

use yii\db\Schema;
use yii\db\Migration;

class m151119_231023_remove_first_course extends Migration
{
    public function up()
    {
        $this->dropForeignKey('meal_ibfk_1', 'meal');
        $this->dropIndex('firstCourse', 'meal');
        $this->dropColumn('meal', 'firstCourse');

        $this->dropForeignKey('meal_ibfk_2', 'meal');
        $this->dropIndex('secondCourse', 'meal');
        $this->dropColumn('meal', 'secondCourse');

        $this->dropForeignKey('meal_ibfk_3', 'meal');
        $this->dropIndex('dessert', 'meal');
        $this->dropColumn('meal', 'dessert');

        $this->dropForeignKey('meal_ibfk_4', 'meal');
        $this->dropIndex('drink', 'meal');
        $this->dropColumn('meal', 'drink');
    }

    public function down()
    {
        echo "m151119_231023_remove_first_course cannot be reverted.\n";
        return false;
    }

    /*
    // Use safeUp/safeDown to run migration code within a transaction
    public function safeUp()
    {
    }

    public function safeDown()
    {
    }
    */
}
