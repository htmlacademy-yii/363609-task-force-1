<?php

use yii\db\Migration;
/**
 * Class m220131_143853_add_users_data
 */
class m220131_143853_add_users_data extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->insert('users', [
            'name' => 'Karrie Buttress',
            'email' => 'kbuttress0@1und1.de',
            'password_hash' => Yii::$app->security->generatePasswordHash('JcfoKBYAB4k'),
            'dt_add' => '2019-08-10',
            'created_at' => time(),
            'updated_at' => time(),
            'status' => \common\models\User::STATUS_ACTIVE,
            'photo' => '/img/man-glasses.jpg',
            'phone' => '64574473047',
            'skype' => 'high-level',
            'birthday' => '1989-11-11',
            'city_id' => 1,
            'about' => 'In est risus, auctor sed, tristique in, tempus sit amet, sem. Fusce consequat.',
        ]);
        $this->insert('users', [
            'name' => 'Bob Aymer',
            'email' => 'baymer1@hp.com',
            'password_hash' => Yii::$app->security->generatePasswordHash('ZEE54kg'),
            'dt_add' => '2018-12-21',
            'created_at' => time(),
            'updated_at' => time(),
            'status' => \common\models\User::STATUS_ACTIVE,
            'photo' => '/img/man-glasses.jpg',
            'phone' => '75531015353',
            'skype' => 'mobile',
            'birthday' => '1989-03-05',
            'city_id' => 2,
            'about' => 'Pellentesque ultrices mattis odio.',
        ]);
        $this->insert('users', [
            'name' => 'Zilvia Boulding',
            'email' => 'zboulding2@macromedia.com',
            'password_hash' => Yii::$app->security->generatePasswordHash('VJyMV1Zat'),
            'dt_add' => '2019-07-25',
            'created_at' => time(),
            'updated_at' => time(),
            'status' => \common\models\User::STATUS_ACTIVE,
            'photo' => '/img/man-glasses.jpg',
            'phone' => '16371407508',
            'skype' => 'Re-engineered',
            'birthday' => '1989-12-30',
            'city_id' => 3,
            'about' => 'Morbi a ipsum. Integer a nibh. In quis justo.',
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->delete('users');
    }
}
