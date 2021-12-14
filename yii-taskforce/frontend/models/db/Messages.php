<?php

namespace frontend\models\db;

use Yii;
use yii\behaviors\TimestampBehavior;
use frontend\models\db\Tasks;
use common\models\User;

/**
 * This is the model class for table "messages".
 *
 * @property int $id
 * @property int|null $task_id
 * @property int|null $user_id
 * @property string|null $message
 * @property int|null $published_at
 *
 * @property Tasks $task
 */
class Messages extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'messages';
    }

    public function behaviors()
    {
        return [
            'timestamp' => [
                'class' => TimestampBehavior::class,
                'attributes' => [
                    \yii\db\ActiveRecord::EVENT_BEFORE_INSERT => ['published_at'],
                ],
                'value' => date('Y-m-d H:i:s'),
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['task_id', 'user_id'], 'integer'],
            [['message', 'published_at'], 'string'],
            [['task_id'], 'exist', 'skipOnError' => false, 'targetClass' => Tasks::class, 'targetAttribute' => ['task_id' => 'id']],
            [['user_id'], 'exist', 'skipOnError' => false, 'targetClass' => User::class, 'targetAttribute' => ['user_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'task_id' => 'Task ID',
            'message' => 'Message',
            'timestamp' => 'Timestamp',
        ];
    }

    /**
     * Gets query for [[Task]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getTask()
    {
        return $this->hasOne(Tasks::class, ['id' => 'task_id']);
    }

    /**
     * Gets query for [[User]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }

    /**
     * Поставит флаг, является ли сообщение - сообщение текущего авторизованного юзера
     *
     * @return array|int[]|string[]
     */
    public function fields()
    {
        $fields = parent::fields();
        $fields['is_mine'] = function () {
            return $this->user_id == Yii::$app->user->identity->id;
        };

        return $fields;
    }

}
