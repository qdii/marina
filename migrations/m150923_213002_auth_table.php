<?php

use yii\db\Schema;
use yii\db\Migration;

class m150923_213002_auth_table extends Migration
{
    public function up()
    {
        $this->createTable('auth', [
            'id'    => $this->primaryKey(),
            'user'  => $this->integer()->notNull(),
            'src'   => $this->string()->notNull(),
            'srcid' => $this->integer()->notNull(),

           ], 'COLLATE = utf8_bin');

        $this->addForeignKey(
            'auth_fk_user',
            'auth',
            'user',
            'user',
            'id',
            'RESTRICT',
            'CASCADE'
        );
    }

    public function down()
    {
        $this->dropForeignKey(
            'auth_fk_user',
            'auth'
        );

        $this->dropTable('auth');
        return true;
    }
}
