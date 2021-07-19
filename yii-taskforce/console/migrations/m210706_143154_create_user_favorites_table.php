<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%user_favorites}}`.
 */
class m210706_143154_create_user_favorites_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%user_favorites}}', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer()->notNull(),
            'favorite_id' => $this->integer()->notNull(),
        ]);

        $this->createIndex(
            '{{%idx-user_favorites_user_id}}',
            '{{%user_favorites}}',
            'user_id'
        );
        $this->addForeignKey(
            '{{%fk-user_favorites_user_id}}',
            '{{%user_favorites}}',
            'user_id',
            '{{%users}}',
            'id',
            'CASCADE'
        );

        $this->addForeignKey(
            '{{%fk-user_favorites_favorite_id}}',
            '{{%user_favorites}}',
            'favorite_id',
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
        $this->dropForeignKey('{{%fk-user_favorites_user_id}}', '{{%user_favorites}}');
        $this->dropIndex('{{%idx-user_favorites_user_id}}' , '{{%user_favorites}}');

        $this->dropForeignKey('{{%fk-user_favorites_favorite_id}}', '{{%user_favorites}}');

        $this->dropTable('{{%user_favorites}}');
    }
}
