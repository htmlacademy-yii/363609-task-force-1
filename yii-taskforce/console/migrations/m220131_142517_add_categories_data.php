<?php

use yii\db\Migration;

/**
 * Class m220131_142517_add_categories_data
 */
class m220131_142517_add_categories_data extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->insert('categories', [
            'name' => 'Переводы',
            'icon' => 'translation'
        ]);
        $this->insert('categories', [
            'name' => 'Уборка',
            'icon' => 'clean'
        ]);
        $this->insert('categories', [
            'name' => 'Переезды',
            'icon' => 'cargo'
        ]);
        $this->insert('categories', [
            'name' => 'Компьютерная помощь',
            'icon' => 'neo'
        ]);
        $this->insert('categories', [
            'name' => 'Ремонт квартирный',
            'icon' => 'flat'
        ]);
        $this->insert('categories', [
            'name' => 'Ремонт техники',
            'icon' => 'repair'
        ]);
        $this->insert('categories', [
            'name' => 'Красота',
            'icon' => 'beauty'
        ]);
        $this->insert('categories', [
            'name' => 'Фото',
            'icon' => 'photo'
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->delete('categories');
    }

}
