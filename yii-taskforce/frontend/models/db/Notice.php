<?php

namespace frontend\models\db;

use yii\db\ActiveQuery;
use yii\db\ActiveRecord;
use common\models\User;
use frontend\models\db\Tasks;

/**
 * This is the model class for table "notice".
 *
 * @property int $id
 * @property int|null $user_id
 * @property int|null $task_id
 * @property int|null $type
 * @property string|null $title
 * @property string|null $text
 * @property int|null $is_read
 *
 * @property User $user
 */
class Notice extends ActiveRecord
{
    public const TYPE_NEW_RESPOND = 1;
    public const TYPE_NEW_MESSAGE = 2;
    public const TYPE_TASK_REFUSE = 3;
    public const TYPE_TASK_START = 4;
    public const TYPE_TASK_END = 5;

    public const TYPE_MAP = [
        self::TYPE_NEW_RESPOND => [
            'title' => 'Новый отклик к заданию',
            'text' => 'Получен новый отклик на задание #TASK#. Посмотреть можно по #LINK#',
            'icon' => 'lightbulb__new-task--executor'
        ],
        self::TYPE_NEW_MESSAGE => [
            'title' => 'Новое сообщение в чате',
            'text' => 'Получено новое сообщение в чате задания #TASK#. Посмотреть можно по #LINK#',
            'icon' => 'lightbulb__new-task--message'
        ],
        self::TYPE_TASK_REFUSE => [
            'title' => 'Отказ от задания исполнителем',
            'text' => 'Исполнитель отказался от задания #TASK#. Посмотреть можно по #LINK#',
            'icon' => 'lightbulb__new-task--executor'
        ],
        self::TYPE_TASK_START => [
            'title' => 'Старт задания',
            'text' => 'Начато задание #TASK#. Посмотреть можно по #LINK#',
            'icon' => 'lightbulb__new-task--executor'
        ],
        self::TYPE_TASK_END => [
            'title' => 'Завершение задания',
            'text' => 'Завершено задание #TASK#. Посмотреть можно по #LINK#',
            'icon' => 'lightbulb__new-task--close'
        ]
    ];

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'notice';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id', 'type', 'is_read', 'task_id'], 'integer'],
            [['text'], 'string'],
            [['title'], 'string', 'max' => 255],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['user_id' => 'id']],
            [['task_id'], 'exist', 'skipOnError' => true, 'targetClass' => Tasks::class, 'targetAttribute' => ['task_id' => 'id']],
            [['is_read'], 'default', 'value' => 0]
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'User ID',
            'type' => 'Type',
            'title' => 'Title',
            'text' => 'Text',
            'is_read' => 'Is Read',
        ];
    }

    /**
     * Gets query for [[User]].
     *
     * @return ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }

    /**
     * Gets query for [[Tasks]].
     *
     * @return ActiveQuery
     */
    public function getTask()
    {
        return $this->hasOne(Tasks::class, ['id' => 'task_id']);
    }
}
