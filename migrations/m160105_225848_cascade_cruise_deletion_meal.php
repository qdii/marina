<?php

use yii\db\Schema;
use yii\db\Migration;

class m160105_225848_cascade_cruise_deletion_meal extends Migration
{
    public function up()
    {
        $this->dropForeignKey('meal_ibfk_6', 'meal');
        $this->addForeignKey(
            'meal_fk_cruise',
            'meal',
            'cruise',
            'cruise',
            'id',
            'cascade',
            'cascade'
        );
    }

    public function down()
    {
        $this->dropForeignKey('meal_fk_cruise', 'meal');
        $this->addForeignKey(
            'meal_ibfk_6',
            'meal',
            'cruise',
            'cruise',
            'id',
            'restrict',
            'cascade'
        );
    }
}
