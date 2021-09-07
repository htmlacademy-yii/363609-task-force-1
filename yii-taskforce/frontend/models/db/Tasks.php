<?php

namespace frontend\models\db;

use common\models\User;
use Yii;
use frontend\models\db\Categories;
use frontend\models\db\Cities;
use frontend\models\db\Replies;
use frontend\models\db\TasksFiles;
use yii\helpers\FileHelper;
use yii\web\UploadedFile;

/**
 * This is the model class for table "tasks".
 *
 * @property int $id
 * @property string|null $dt_add
 * @property int|null $category_id
 * @property string|null $description
 * @property string|null $expire
 * @property string|null $name
 * @property string|null $address
 * @property int|null $budget
 * @property int|null $customer_id
 * @property int|null $city_id
 * @property int $executor_id
 * @property float|null $lat
 * @property float|null $long
 */
class Tasks extends \yii\db\ActiveRecord
{
    public $files;

    /*
     * статусы задач
     */
    const STATUS_NEW = 1;
    const STATUS_CANCELED = 2;
    const STATUS_IN_WORK = 3;
    const STATUS_COMPLETED = 4;
    const STATUS_FAILED = 5;

    const STATUSES_LIST = [
        self::STATUS_NEW => 'Новое',
        self::STATUS_CANCELED => 'Отменено',
        self::STATUS_IN_WORK => 'В работе',
        self::STATUS_COMPLETED => 'Выполнено',
        self::STATUS_FAILED => 'Провалено',
    ];

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'tasks';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['dt_add', 'expire'], 'date', 'format' => 'php:Y-m-d'],
            [['category_id', 'budget', 'status', 'city_id', 'executor_id', 'customer_id'], 'integer'],
            [['address'], 'string'],
            [['lat', 'long'], 'number'],
            [['files'], 'safe'],
            [['name', 'description', 'category_id'], 'required'],
            [['name'], 'string', 'min' => 10, 'max' => 255],
            [['description'], 'string', 'min' => 30],
            [['category_id'], 'exist', 'skipOnError' => false, 'targetClass' => Categories::class, 'targetAttribute' => ['category_id' => 'id']]
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'dt_add' => 'Dt Add',
            'category_id' => 'Категория',
            'description' => 'Подробности задания',
            'expire' => 'Срок исполнения',
            'name' => 'Мне нужно',
            'address' => 'Локация',
            'budget' => 'Бюджет',
            'lat' => 'Lat',
            'long' => 'Long',
            'files' => 'Файлы'
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCategories()
    {
        return $this->hasOne(Categories::class, ['id' => 'category_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCity()
    {
        return $this->hasOne(Cities::class, ['id' => 'city']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getReplies()
    {
        return $this->hasMany(Replies::class, ['task_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCustomer()
    {
        return $this->hasOne(User::class, ['id' => 'customer_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getFiles()
    {
        return $this->hasMany(TasksFiles::class, ['id_task' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::class, ['id' => 'customer_id']);
    }

    /**
     * @return array|Categories[]|\yii\db\ActiveRecord[]
     */
    public function getAllCategories()
    {
        return Categories::find()->select(['id', 'name'])->all();
    }

    public function uploadFile($model)
    {
        if(FileHelper::createDirectory($_SERVER['DOCUMENT_ROOT'] . '/uploads')) {
            $files = UploadedFile::getInstances($model, 'files');
            foreach ($files as $file) {
                $save = $file->saveAs($_SERVER['DOCUMENT_ROOT'] . '/uploads/' . $file->baseName . '.' . $file->extension);
                if($save) {
                    $modelFileTask = new TasksFiles();
                    $modelFileTask->id_task = $this->id;
                    $modelFileTask->name = $file->baseName;
                    $modelFileTask->path = '/uploads/' . $file->baseName . '.' . $file->extension;
                    $modelFileTask->absolute_path = $_SERVER['DOCUMENT_ROOT'] . '/uploads/' . $file->baseName . '.' . $file->extension;
                    $modelFileTask->save();
                }
            }
        }
    }

}
