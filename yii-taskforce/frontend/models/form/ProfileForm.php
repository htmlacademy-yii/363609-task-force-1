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

    public $photoWork;

    public function __construct($config = [])
    {
        parent::__construct($config);

        $this->attributes = Yii::$app->user->identity->attributes;
        $this->specialization = $this->getUserSpecialization();
        $this->photoWork = UserFiles::find()
            ->where(['id_user' => Yii::$app->user->identity->id])
            ->select('path')
            ->all();
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
            [['photo', 'specialization'], 'safe'],
            [['name', 'email'], 'required']
        ];
    }

    public function attributeLabels()
    {
        return [
            'setting_new_message' => 'Новое сообщение',
            'setting_action_task' => 'Действия по заданию',
            'setting_new_review' => 'Новый отзыв',
            'setting_show_contact' => 'Показывать мои контакты только заказчику',
            'setting_hide_profile' => 'Не показывать мой профиль',
            'name' => 'Имя',
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
     * @throws \yii\base\Exception
     */
    public function uploadFiles()
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

    /**
     * @return string|void
     * @throws \yii\base\Exception
     */
    public function uploadFile()
    {
        if (FileHelper::createDirectory($_SERVER['DOCUMENT_ROOT'] . '/uploads')) {
            $file = UploadedFile::getInstance($this, 'photo');
            if(!empty($file)) {
                $path = '/uploads/' . $file->baseName . '.' . $file->extension;
                $save = $file->saveAs($_SERVER['DOCUMENT_ROOT'] . $path);
                if($save) {
                    return $path;
                }
            }
        }
    }

    /**
     * @param $user
     */
    public function setSpecialization($user)
    {
        UsersCategories::deleteAll(['user_id' => $user->id]);
        $auth = Yii::$app->authManager;
        $user_permission = $auth->getRole('executor');
        $auth->revoke($user_permission, $user->id);
        if(!empty($this->specialization) && is_array($this->specialization)) {
            foreach ($this->specialization as $item) {
                $model = new UsersCategories();
                $model->user_id = $user->id;
                $model->category_id = $item;
                $model->save();
            }
            $auth->assign($user_permission, $user->id);
        }
    }

    /**
     * @param $user
     */
    public function setPassword($user) {
        if(!empty($this->password)) {
            if($this->password === $this->passwordRepeat) {
                $user->setPassword($this->password);
                return;
            }
            $this->addError('password', 'Введённые пароли не совпадают');
        }
    }

    /**
     * @return array
     * @throws \yii\base\Exception
     */
    public function save()
    {
        $user = Yii::$app->user->identity;
        if ($this->validate()) {

            if($photo = $this->uploadFile()) {
                $this->photo = $photo;
            }
            else {
                $this->photo = $user->photo;
            }

            $user->setAttributes($this->attributes);
            $this->setSpecialization($user);
            $this->setPassword($user);

            if(!$user->save()) {
                return $user->getFirstErrors();
            }
        }

        $this->photo = $user->photo;
        return $this->getFirstErrors();
    }

}
