<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%tasks_files}}`.
 */
class m210803_141835_create_tasks_files_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%tasks_files}}', [
            'id' => $this->primaryKey(),
            'id_task' => $this->integer(),
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
        $this->dropTable('{{%tasks_files}}');
    }
}
