<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%users_categories}}`.
 */
class m210622_143042_create_users_categories_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%users_categories}}', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer()->notNull(),
            'category_id' => $this->integer()->notNull()
        ]);

        $this->createIndex(
            '{{%idx-users_categories_user_id}}',
            '{{%users_categories}}',
            'user_id'
        );
        $this->addForeignKey(
            '{{%fk-users_categories_user_id}}',
            '{{%users_categories}}',
            'user_id',
            '{{%users}}',
            'id',
            'CASCADE'
        );

        $this->createIndex(
            '{{%idx-users_categories_category_id}}',
            '{{%users_categories}}',
            'category_id'
        );
        $this->addForeignKey(
            '{{%fk-users_categories_category_id}}',
            '{{%users_categories}}',
            'category_id',
            '{{%categories}}',
            'id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('{{%fk-users_categories_user_id}}', '{{%users_categories}}');
        $this->dropIndex('{{%idx-users_categories_user_id}}' , '{{%users_categories}}');

        $this->dropForeignKey('{{%fk-users_categories_category_id}}', '{{%users_categories}}');
        $this->dropIndex('{{%idx-users_categories_category_id}}' , '{{%users_categories}}');

        $this->dropTable('{{%users_categories}}');
    }
}
