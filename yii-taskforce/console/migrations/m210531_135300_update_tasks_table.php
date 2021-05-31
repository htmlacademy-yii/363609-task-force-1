<?php

use yii\db\Migration;

/**
 * Class m210531_135300_update_tasks_table
 */
class m210531_135300_update_tasks_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('tasks', 'status', $this->integer(11)->after('id')->defaultValue(1));
        $this->createIndex('idx-tasks-category_id', '{{%tasks}}', 'category_id');
        $this->addForeignKey(
            'fk-tasks-category_id',
            '{{%tasks}}',
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
        echo "m210531_135300_update_tasks_table cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m210531_135300_update_tasks_table cannot be reverted.\n";

        return false;
    }
    */
}
