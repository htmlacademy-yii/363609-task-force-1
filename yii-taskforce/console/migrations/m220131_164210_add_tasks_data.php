<?php

use yii\db\Migration;

/**
 * Class m220131_164210_add_tasks_data
 */
class m220131_164210_add_tasks_data extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->insert('tasks', [
            'status' => 1,
            'dt_add' => '2019-03-09',
            'category_id' => 1,
            'description' => 'Suspendisse potenti. In eleifend quam a odio. In hac habitasse platea dictumst.',
            'expire' => '2019-11-15',
            'name' => 'enable impactful technologies',
            'address' => '1 Eagan Crossing,6587',
            'budget' => 6587,
            'lat' => '6.9641667',
            'long' => '158.2083333',
            'city_id' => '',
            'customer_id' => 1
        ]);
        $this->insert('tasks', [
            'status' => 2,
            'dt_add' => '2021-10-12',
            'category_id' => 1,
            'description' => 'Некое тестовое задание, в котором нужно что-то сделать',
            'expire' => '2022-10-12',
            'name' => 'Некое тестовое задание',
            'address' => 'Россия, Челябинск',
            'budget' => 1000,
            'lat' => '55.159902',
            'long' => '61.402554',
            'city_id' => 1,
            'customer_id' => 1
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->delete('tasks');
    }

}
