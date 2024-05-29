<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%item}}`.
 */
class m240529_004232_create_item_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%item}}', [
            'id' => $this->string(36)->notNull()->append('PRIMARY KEY'),
            'level_id' => $this->string(36),
            'rack_id' => $this->string(36),
            'nama_item' => $this->string(200),
            'deskripsi_item' => $this->string(1000),
            'created_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP'),
            'updated_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP')->append('ON UPDATE CURRENT_TIMESTAMP')
        ]);        
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%item}}');
    }
}
