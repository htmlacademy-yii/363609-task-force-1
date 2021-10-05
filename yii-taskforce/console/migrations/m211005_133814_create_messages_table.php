<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%messages}}`.
 */
class m211005_133814_create_messages_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%messages}}', [
            'id' => $this->primaryKey(),
            'task_id' => $this->integer(),
            'user_id' => $this->integer(),
            'message' => $this->text(),
            'timestamp' => $this->integer()
        ]);

        $this->createIndex(
            '{{%idx-messages_task_id}}',
            '{{%messages}}',
            'task_id'
        );
        $this->addForeignKey(
            '{{%fk-messages_task_id}}',
            '{{%messages}}',
            'task_id',
            '{{%tasks}}',
            'id',
            'CASCADE'
        );

        $this->createIndex(
            '{{%idx-messages_user_id}}',
            '{{%messages}}',
            'user_id'
        );
        $this->addForeignKey(
            '{{%fk-messages_user_id}}',
            '{{%messages}}',
            'user_id',
            '{{%users}}',
            'id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('{{%fk-messages_task_id}}', '{{%messages}}');
        $this->dropIndex('{{%idx-messages_task_id}}' , '{{%messages}}');

        $this->dropForeignKey('{{%fk-messages_user_id}}', '{{%users}}');
        $this->dropIndex('{{%idx-messages_user_id}}' , '{{%users}}');

        $this->dropTable('{{%messages}}');
    }
}
