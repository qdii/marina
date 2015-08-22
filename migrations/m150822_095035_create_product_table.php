<?php

use yii\db\Schema;
use yii\db\Migration;

class m150822_095035_create_product_table extends Migration
{
    public function safeUp()
    {
        $this->createTable('vendor', [
            'id'   => $this->primaryKey(),
            'name' => $this->string()->notNull(),
        ], 'COLLATE = utf8_bin');

        $this->createTable('product', [
            'id'       => $this->primaryKey(),
            'name'     => $this->string()->notNull(),
            'vendor'   => $this->integer(),
            'unit'     => $this->integer(),
            'quantity' => $this->integer()->notNull(),
        ], 'COLLATE = utf8_bin');

        $this->createTable('proportion', [
            'ingredient' => $this->integer(),
            'product'    => $this->integer(),
            'weight'     => $this->float()->notNull(),
        ], 'COLLATE = utf8_bin');

        $this->addForeignKey(
            'product_fk_vendor',
            'product',
            'vendor',
            'vendor',
            'id',
            'RESTRICT',
            'CASCADE'
        );

        $this->addForeignKey(
            'product_fk_unit',
            'product',
            'unit',
            'unit',
            'id',
            'RESTRICT',
            'CASCADE'
        );

        $this->addForeignKey(
            'proportion_fk_product',
            'proportion',
            'product',
            'product',
            'id',
            'RESTRICT',
            'CASCADE'
        );

        $this->addForeignKey(
            'proportion_fk_ingredient',
            'proportion',
            'ingredient',
            'ingredient',
            'id',
            'RESTRICT',
            'CASCADE'
        );

        $this->addColumn(
            'ingredient',
            'weight',
            'float'
        );
    }

    public function safeDown()
    {
        $this->dropColumn('ingredient', 'weight');

        $this->dropForeignKey(
            'proportion_fk_ingredient',
            'proportion'
        );

        $this->dropForeignKey(
            'proportion_fk_product',
            'proportion'
        );

        $this->dropForeignKey(
            'product_fk_vendor',
            'product'
        );

        $this->dropForeignKey(
            'product_fk_unit',
            'product'
        );

        $this->dropTable('proportion');
        $this->dropTable('product');
        $this->dropTable('vendor');

        return true;
    }
}
