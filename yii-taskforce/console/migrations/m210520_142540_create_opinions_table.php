<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%opinions}}`.
 */
class m210520_142540_create_opinions_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%opinions}}', [
            'id' => $this->primaryKey(),
            'dt_add' => $this->date(),
            'rate' => $this->integer(10),
            'description' => $this->text()
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%opinions}}');
    }
}
