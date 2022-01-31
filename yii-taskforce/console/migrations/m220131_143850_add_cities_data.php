<?php

use yii\db\Migration;

/**
 * Class m220131_143850_add_cities_data
 */
class m220131_143850_add_cities_data extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->insert('cities', [
            'city' => 'Челябинск',
            'lat' => '55.1603659',
            'long' => '61.4007858',
        ]);
        $this->insert('cities', [
            'city' => 'Сочи',
            'lat' => '43.5855829',
            'long' => '39.7231419',
        ]);
        $this->insert('cities', [
            'city' => 'Новосибирск',
            'lat' => '55.0281016',
            'long' => '82.9210575',
        ]);
        $this->insert('cities', [
            'city' => 'Архангельск',
            'lat' => '64.5392985',
            'long' => '40.5170083',
        ]);
        $this->insert('cities', [
            'city' => 'Екатеринбург',
            'lat' => '56.8386326',
            'long' => '60.6054887',
        ]);
        $this->insert('cities', [
            'city' => 'Владивосток',
            'lat' => '43.1163807',
            'long' => '131.882348',
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->delete('cities');
    }
}
