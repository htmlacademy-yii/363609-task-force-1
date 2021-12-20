<?php

namespace frontend\models\form;

use frontend\models\db\Categories;
use frontend\models\db\Cities;
use frontend\models\db\UsersCategories;
use yii\base\Model;
use Yii;

class ProfileForm extends Model
{
    public $name;
    public $email;
    public $city_id;
    public $birthday;
    public $about;
    public $specialization;
    public $password;
    public $passwordRepeat;
    public $phone;
    public $skype;
    public $telegram;
    public $setting_new_message;
    public $setting_action_task;
    public $setting_new_review;
    public $setting_show_contact;
    public $setting_hide_profile;
    public $photo;
    public $file;

    public function __construct($config = [])
    {
        parent::__construct($config);

        $this->attributes = Yii::$app->user->identity->attributes;
        $this->specialization = $this->getUserSpecialization();
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'email', 'photo', 'phone', 'skype', 'telegram'], 'string', 'max' => 255],
            [['birthday'], 'date'],
            [['about'], 'string'],
            [['setting_new_message', 'setting_action_task', 'setting_new_review', 'setting_show_contact', 'setting_hide_profile'], 'boolean'],
            [['city_id'], 'integer']
        ];
    }

    public function getCityList()
    {
        return Cities::find()->select(['city'])->indexBy('id')->column();
    }

    public function getSpecializationList()
    {
        return Categories::find()->select(['name'])->indexBy('id')->column();
    }

    public function getUserSpecialization()
    {
        return UsersCategories::find()
            ->where(['user_id' => Yii::$app->user->identity->id])
            ->select(['category_id'])
            ->column();
    }


}
