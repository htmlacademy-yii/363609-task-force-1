<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%replies}}`.
 */
class m210520_142613_create_replies_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%replies}}', [
            'id' => $this->primaryKey(),
            'dt_add' => $this->date(),
            'rate' => $this->integer(10),
            'description' => $this->text(),
            'user_id' => $this->integer(),
            'task_id' => $this->integer(),
            'status' => $this->tinyInteger(1)
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%replies}}');
    }
}
