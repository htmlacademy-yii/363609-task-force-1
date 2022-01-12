<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%notice}}`.
 */
class m220110_160214_create_notice_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%notice}}', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer(),
            'task_id' => $this->integer(),
            'type' => $this->tinyInteger(),
            'title' => $this->string(255),
            'text' => $this->text(),
            'is_read' => $this->boolean()->defaultValue(0)
        ]);

        $this->createIndex(
            '{{%idx-notice_user_id}}',
            '{{%notice}}',
            'user_id'
        );
        $this->addForeignKey(
            '{{%fk-notice_user_id}}',
            '{{%notice}}',
            'user_id',
            '{{%users}}',
            'id',
            'CASCADE'
        );

        $this->createIndex(
            '{{%idx-notice_task_id}}',
            '{{%notice}}',
            'task_id'
        );
        $this->addForeignKey(
            '{{%fk-notice_task_id}}',
            '{{%notice}}',
            'task_id',
            '{{%tasks}}',
            'id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%notice}}');
    }
}
