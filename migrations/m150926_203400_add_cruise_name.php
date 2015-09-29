<?php

use yii\db\Schema;
use yii\db\Migration;

class m150926_203400_add_cruise_name extends Migration
{
    public function up()
    {
        $this->addColumn('cruise', 'name', Schema::TYPE_STRING . " DEFAULT 'unnamed'");
    }

    public function down()
    {
        $this->dropColumn('cruise', 'name');
    }
}
