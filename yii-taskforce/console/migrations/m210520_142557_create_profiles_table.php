<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%profiles}}`.
 */
class m210520_142557_create_profiles_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%profiles}}', [
            'id' => $this->primaryKey(),
            'address' => $this->text(),
            'bd' => $this->date(),
            'about' => $this->text(),
            'phone' => $this->string(64),
            'skype' => $this->string(64),
            'user_id' => $this->integer()
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%profiles}}');
    }
}
