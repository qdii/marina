<?php

use yii\db\Schema;
use yii\db\Migration;

class m150923_222415_extend_user_table extends Migration
{
    public function up()
    {
        $this->addColumn('user', 'email', 'string not null');
    }

    public function down()
    {
        $this->dropColumn('user', 'email');
    }
}
