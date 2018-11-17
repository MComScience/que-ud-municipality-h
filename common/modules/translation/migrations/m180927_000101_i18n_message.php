<?php

use yii\db\Schema;

class m180927_000101_i18n_message extends \yii\db\Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';
        }
        
        $this->createTable('i18n_message', [
            'id' => $this->integer(11)->notNull(),
            'language' => $this->string(16)->notNull(),
            'translation' => $this->text(),
            'PRIMARY KEY ([[id]], [[language]])',
            ], $tableOptions);
                
    }

    public function down()
    {
        $this->dropTable('i18n_message');
    }
}
