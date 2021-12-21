<?php

namespace frontend\models\form;

use frontend\models\db\Categories;
use frontend\models\db\Cities;
use frontend\models\db\TasksFiles;
use frontend\models\db\UserFiles;
use frontend\models\db\UsersCategories;
use yii\base\Model;
use Yii;
use yii\helpers\FileHelper;
use yii\web\UploadedFile;

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
            [['name', 'email', 'phone', 'skype', 'telegram'], 'string', 'max' => 255],
            [['birthday'], 'date', 'format' => 'php:Y-m-d'],
            [['about', 'password', 'passwordRepeat'], 'string'],
            [['setting_new_message', 'setting_action_task', 'setting_new_review', 'setting_show_contact', 'setting_hide_profile'], 'boolean'],
            [['city_id'], 'integer'],
            [['photo'], 'safe']
        ];
    }

    public function attributeLabels()
    {
        return [
            'setting_new_message' => 'Новое сообщение',
            'setting_action_task' => 'Действия по заданию',
            'setting_new_review' => 'Новый отзыв',
            'setting_show_contact' => 'Показывать мои контакты только заказчику',
            'setting_hide_profile' => 'Не показывать мой профиль'
        ];
    }

    /**
     * @return array
     */
    public function getCityList()
    {
        return Cities::find()->select(['city'])->indexBy('id')->column();
    }

    /**
     * @return array
     */
    public function getSpecializationList()
    {
        return Categories::find()->select(['name'])->indexBy('id')->column();
    }

    /**
     * @return array
     */
    public function getUserSpecialization()
    {
        return UsersCategories::find()
            ->where(['user_id' => Yii::$app->user->identity->id])
            ->select(['category_id'])
            ->column();
    }

    /**
     * @param $user
     * @throws \yii\base\Exception
     */
    public function uploadFile()
    {
        if (FileHelper::createDirectory($_SERVER['DOCUMENT_ROOT'] . '/uploads')) {
            $files = UploadedFile::getInstancesByName('file');
            foreach ($files as $file) {
                $save = $file->saveAs($_SERVER['DOCUMENT_ROOT'] . '/uploads/' . $file->baseName . '.' . $file->extension);
                if ($save) {
                    $modelFileUser = new UserFiles();
                    $modelFileUser->id_user = Yii::$app->user->identity->id;
                    $modelFileUser->name = $file->baseName;
                    $modelFileUser->path = '/uploads/' . $file->baseName . '.' . $file->extension;
                    $modelFileUser->absolute_path = $_SERVER['DOCUMENT_ROOT'] . '/uploads/' . $file->baseName . '.' . $file->extension;
                    $modelFileUser->save();
                }
            }
        }
    }

    public function save()
    {
        if ($this->validate()) {
            $user = Yii::$app->user->identity;
            $user->setAttributes($this->attributes);
            $user->save();

            return $user->getFirstErrors();
        }

        return $this->getFirstErrors();
    }

}
