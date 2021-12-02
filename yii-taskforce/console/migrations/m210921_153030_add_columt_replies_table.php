<?php

use yii\db\Migration;

/**
 * Class m210921_153030_add_columt_replies_table
 */
class m210921_153030_add_columt_replies_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('replies', 'price', $this->integer());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('replies', 'price');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m210921_153030_add_columt_replies_table cannot be reverted.\n";

        return false;
    }
    */
}
