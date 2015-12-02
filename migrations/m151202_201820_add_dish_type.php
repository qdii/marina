<?php

use yii\db\Schema;
use yii\db\Migration;

class m151202_201820_add_dish_type extends Migration
{
    public function up()
    {
        $this->createTable('dish_type', [
            'dish' => $this->integer()->notNull(),
            'type' => $this->integer()
        ]);
        $this->addForeignKey([
            'dish_type_fk_dish',
            'dish_type',
            'dish',
            'dish',
            'id',
            'RESTRICT',
            'CASCADE'
        ]);
    }

    public function down()
    {
        $this->dropForeignKey(
            'dish_type_fk_dish',
            'dish'
        );

        $this->dropTable('dish_type');
        return true;
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
