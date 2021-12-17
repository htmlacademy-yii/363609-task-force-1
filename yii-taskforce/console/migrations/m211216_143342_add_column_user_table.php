<?php

use yii\db\Migration;

/**
 * Class m211216_143342_add_column_user_table
 */
class m211216_143342_add_column_user_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('users', 'phone', $this->string(255));
        $this->addColumn('users', 'skype', $this->string(255));
        $this->addColumn('users', 'telegram', $this->string(255));
        $this->addColumn('users', 'birthday', $this->date());
        $this->addColumn('users', 'about', $this->text());
        $this->addColumn('users', 'setting_new_message', $this->boolean()->defaultValue(1));
        $this->addColumn('users', 'setting_action_task', $this->boolean()->defaultValue(1));
        $this->addColumn('users', 'setting_new_review', $this->boolean()->defaultValue(1));
        $this->addColumn('users', 'setting_show_contact', $this->boolean()->defaultValue(1));
        $this->addColumn('users', 'setting_hide_profile', $this->boolean()->defaultValue(1));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('users', 'phone');
        $this->dropColumn('users', 'skype');
        $this->dropColumn('users', 'telegram');
        $this->dropColumn('users', 'birthday');
        $this->dropColumn('users', 'about');
        $this->dropColumn('users', 'setting_new_message');
        $this->dropColumn('users', 'setting_action_task');
        $this->dropColumn('users', 'setting_new_review');
        $this->dropColumn('users', 'setting_show_contact');
        $this->dropColumn('users', 'setting_hide_profile');
    }

}
