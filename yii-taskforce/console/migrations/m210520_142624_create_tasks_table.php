<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%tasks}}`.
 */
class m210520_142624_create_tasks_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%tasks}}', [
            'id' => $this->primaryKey(),
            'dt_add' => $this->date(),
            'category_id' => $this->integer(11),
            'description' => $this->text(),
            'expire' => $this->date(),
            'name' => $this->string(255),
            'address' => $this->text(),
            'budget' => $this->integer(11),
            'lat' => $this->double(5,7),
            'long' => $this->double(5,7),
            'customer_id' => $this->integer(11),
            'executor_id' => $this->integer(11),
            'city_id' => $this->integer(11)
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%tasks}}');
    }
}
