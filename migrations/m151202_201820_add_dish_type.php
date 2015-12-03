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
        ], 'ENGINE=InnoDB DEFAULT CHARSET=utf8');
        $this->addPrimaryKey('dish_primary_key', 'dish_type', ['dish', 'type']);
        $this->addForeignKey(
            'dish_type_fk_dish',
            'dish_type',
            'dish',
            'dish',
            'id',
            'RESTRICT',
            'CASCADE'
        );
    }

    public function down()
    {
        $this->dropForeignKey(
            'dish_primary_key',
            'dish_type_fk_dish',
            'dish'
        );
        $this->dropPrimaryKey('dish_primary_key', 'dish_type');

        $this->dropTable('dish_type');
        return true;
    }
}
