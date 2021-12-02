<?php

use yii\db\Migration;

/**
 * Class m211201_143345_update_users_table
 */
class m211201_143345_update_users_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->renameColumn('users', 'city', 'city_id');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->renameColumn('users', 'city_id', 'city');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m211201_143345_update_users_table cannot be reverted.\n";

        return false;
    }
    */
}
