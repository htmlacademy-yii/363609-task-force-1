<?php

namespace frontend\models\db;

use common\models\User;
use src\helpers\CancelAction;
use src\helpers\DoneAction;
use src\helpers\RefuseAction;
use src\helpers\RespondAction;
use Yii;
use yii\base\Exception;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;
use yii\db\Expression;
use yii\helpers\FileHelper;
use yii\web\UploadedFile;

/**
 * This is the model class for table "tasks".
 *
 * @property int $id
 * @property string|null $dt_add
 * @property int|null $category_id
 * @property int|null $status
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
class Tasks extends ActiveRecord
{
    public $files;
    public $coordinate;

    /*
     * статусы задач
     */
    public const STATUS_NEW = 1;
    public const STATUS_CANCELED = 2;
    public const STATUS_IN_WORK = 3;
    public const STATUS_COMPLETED = 4;
    public const STATUS_FAILED = 5;

    public const STATUSES_LIST = [
        self::STATUS_NEW => 'Новое',
        self::STATUS_CANCELED => 'Отменено',
        self::STATUS_IN_WORK => 'В работе',
        self::STATUS_COMPLETED => 'Выполнено',
        self::STATUS_FAILED => 'Провалено',
    ];

    /*
     * доступные действия с заданиями
     */
    public const ACTION_CANCEL = 'cancel';
    public const ACTION_RESPOND = 'respond';
    public const ACTION_DONE = 'done';
    public const ACTION_REFUSE = 'refuse';

    public const ACTIONS_LIST = [
        self::ACTION_CANCEL => 'Отменить',
        self::ACTION_RESPOND => 'Откликнуться',
        self::ACTION_DONE => 'Выполнено',
        self::ACTION_REFUSE => 'Отказаться',
    ];

    private const AVAILABLE_ACTIONS_MAP = [
        CancelAction::class,
        DoneAction::class,
        RefuseAction::class,
        RespondAction::class
    ];

    public function behaviors()
    {
        return [
            'timestamp' => [
                'class' => TimestampBehavior::class,
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['dt_add'],
                ],
                'value' => new Expression('CURRENT_DATE()'),
            ],
        ];
    }

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
            [['expire'], 'date', 'format' => 'php:Y-m-d'],
            [['category_id', 'budget', 'status', 'city_id', 'executor_id', 'customer_id'], 'integer'],
            [['address', 'coordinate'], 'string'],
            [['lat', 'long'], 'number'],
            [['files'], 'safe'],
            [['status'], 'default', 'value' => self::STATUS_NEW],
            [['name', 'description', 'category_id'], 'required'],
            [['name'], 'string', 'min' => 10, 'max' => 255],
            [['description'], 'string', 'min' => 30],
            [['category_id'], 'exist', 'skipOnError' => false, 'targetClass' => Categories::class, 'targetAttribute' => ['category_id' => 'id']],
            [['customer_id'], 'exist', 'skipOnError' => false, 'targetClass' => User::class, 'targetAttribute' => ['customer_id' => 'id']],
            [['executor_id'], 'exist', 'skipOnError' => false, 'targetClass' => User::class, 'targetAttribute' => ['executor_id' => 'id']]
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
     * @return ActiveQuery
     */
    public function getCategories()
    {
        return $this->hasOne(Categories::class, ['id' => 'category_id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getCity()
    {
        return $this->hasOne(Cities::class, ['id' => 'city']);
    }

    /**
     * @return ActiveQuery
     */
    public function getReplies()
    {
        return $this->hasMany(Replies::class, ['task_id' => 'id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getCustomer()
    {
        return $this->hasOne(User::class, ['id' => 'customer_id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getExecutor()
    {
        return $this->hasOne(User::class, ['id' => 'executor_id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getFiles()
    {
        return $this->hasMany(TasksFiles::class, ['id_task' => 'id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getMessages()
    {
        return $this->hasMany(Messages::class, ['task_id' => 'id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::class, ['id' => 'customer_id']);
    }

    /**
     * @return array|Categories[]|ActiveRecord[]
     */
    public function getAllCategories()
    {
        return Categories::find()->select(['id', 'name'])->all();
    }

    /**
     * @throws Exception
     */
    public function uploadFile()
    {
        if (FileHelper::createDirectory($_SERVER['DOCUMENT_ROOT'] . '/uploads')) {
            $files = UploadedFile::getInstances($this, 'files');
            foreach ($files as $file) {
                $save = $file->saveAs($_SERVER['DOCUMENT_ROOT'] . '/uploads/' . $file->baseName . '.' . $file->extension);
                if ($save) {
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

    /**
     * @return int
     */
    public function getExecutorId(): ?int
    {
        return $this->executor_id;
    }

    /**
     * @return int|null
     */
    public function getCustomerId(): ?int
    {
        return $this->customer_id;
    }

    /**
     * @return array
     */
    public function getAvailableActions(): array
    {
        $currentUser = Yii::$app->user->identity->id;

        $modelActions = array_filter(self::AVAILABLE_ACTIONS_MAP, function ($action) use ($currentUser) {
            return call_user_func([$action, 'checkPermission'], $this, $currentUser);
        });

        $actions = [];
        foreach ($modelActions as $modelAction) {
            $actions[] = $modelAction::getAction();
        }
        return $actions;
    }

    /**
     * @param $action
     * @return int|void|null
     */
    public function getNextStatus($action): ?int
    {
        switch (true) {

            case $action === self::ACTION_CANCEL && $this->status === self::STATUS_NEW:

                return self::STATUS_CANCELED;

            case $action == self::ACTION_RESPOND && $this->status == self::STATUS_NEW:

                return self::STATUS_IN_WORK;

            case $action === self::ACTION_DONE && $this->status == self::STATUS_IN_WORK:

                return self::STATUS_COMPLETED;

            case $action === self::ACTION_REFUSE && $this->status == self::STATUS_IN_WORK:

                return self::STATUS_FAILED;

            default:
                return null;

        }
    }

}
