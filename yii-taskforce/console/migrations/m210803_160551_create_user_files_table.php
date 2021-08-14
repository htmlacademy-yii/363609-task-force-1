<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%user_files}}`.
 */
class m210803_160551_create_user_files_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%user_files}}', [
            'id' => $this->primaryKey(),
            'id_user' => $this->integer(),
            'name' => $this->string(255),
            'path' => $this->string(255),
            'absolute_path' => $this->string(255),
            'created_at' => $this->integer(),
            'updated_at' => $this->integer(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%user_files}}');
    }
}
