<?php

use yii\db\Schema;

class m180927_000101_i18n_source_message extends \yii\db\Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';
        }
        
        $this->createTable('i18n_source_message', [
            'id' => $this->primaryKey(),
            'category' => $this->string(32),
            'message' => $this->text(),
            ], $tableOptions);
                
    }

    public function down()
    {
        $this->dropTable('i18n_source_message');
    }
}
