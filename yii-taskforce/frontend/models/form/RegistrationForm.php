<?php

namespace frontend\models\form;

use common\models\User;
use frontend\models\db\Cities;
use yii\helpers\ArrayHelper;
use Yii;

class RegistrationForm extends User
{
    public $email;
    public $name;
    public $city;
    public $password;

    /**
     * @return array
     */
    public function rules()
    {
        return [
            [['city'], 'integer'],
            [['email', 'name', 'password'], 'string', 'max' => 255],
            [['email', 'name', 'city'], 'required'],
            [['email'], 'unique'],
        ];
    }

    /**
     * @return string[]
     */
    public function attributeLabels()
    {
        return [
            'email' => 'Электронная почта',
            'name' => 'Ваше имя',
            'city' => 'Город проживания',
            'password' => 'Пароль',
        ];
    }

    /**
     * @return array
     */
    public function getCitiesList()
    {
        return ArrayHelper::map(Cities::find()->select(['id', 'city'])->all(), 'id', 'city');
    }

    /**
     * @return User
     */
    public function register()
    {
        $user = new User();
        $user->email = $this->email;
        $user->name = $this->name;
        $user->city_id = $this->city;
        $user->setPassword($this->password);
        if($user->save()) {
            $auth = Yii::$app->authManager;
            $user_permission = $auth->getRole('executor');
            $auth->assign($user_permission, $user->id);
        }
        return $user;
    }

}
