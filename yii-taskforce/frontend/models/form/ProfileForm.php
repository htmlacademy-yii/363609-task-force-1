<?php

namespace frontend\models\form;

use yii\base\Model;
use Yii;

class ProfileForm extends Model
{
    public string $name;
    public ?string $email;
    public ?int $city;
    public ?string $birthday;
    public ?string $about;
    public ?array $specialization;
    public ?string $password;
    public ?string $passwordRepeat;
    public ?string $phone;
    public ?string $skype;
    public ?string $telegram;
    public bool $setting_new_message;
    public bool $setting_action_task;
    public bool $setting_new_review;
    public bool $setting_show_contact;
    public bool $setting_hide_profile;
    public ?string $photo;
    public $file;

    public function __construct($config = [])
    {
        parent::__construct($config);

        $this->attributes = Yii::$app->user->identity->attributes;
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
            [['setting_new_message', 'setting_action_task', 'setting_new_review', 'setting_show_contact', 'setting_hide_profile'], 'boolean']
        ];
    }


}
