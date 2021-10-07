<?php

namespace frontend\models\db;

use common\models\User;
use Yii;
use yii\behaviors\TimestampBehavior;
use frontend\models\db\Tasks;

/**
 * This is the model class for table "replies".
 *
 * @property int $id
 * @property string|null $dt_add
 * @property int|null $rate
 * @property string|null $description
 */
class Replies extends \yii\db\ActiveRecord
{
    /**
     * константы статусов откликов
     */
    public const STATUS_NEW = 1;
    public const STATUS_ACCEPT = 2;
    public const STATUS_REJECT = 3;

    /**
     * @return array[]
     */
    public function behaviors()
    {
        return [
            'timestamp' => [
                'class' => TimestampBehavior::class,
                'attributes' => [
                    \yii\db\ActiveRecord::EVENT_BEFORE_INSERT => ['dt_add'],
                ],
                'value' => new \yii\db\Expression('CURRENT_DATE()'),
            ],
        ];
    }
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'replies';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['rate', 'user_id', 'task_id', 'status', 'price'], 'integer'],
            [['description'], 'string'],
            [['status'], 'default', 'value' => self::STATUS_NEW],
            [['user_id'], 'exist', 'skipOnError' => false, 'targetClass' => User::class, 'targetAttribute' => ['user_id' => 'id']],
            [['task_id'], 'exist', 'skipOnError' => false, 'targetClass' => Tasks::class, 'targetAttribute' => ['task_id' => 'id']],
            [['price'], 'integer', 'min' => 0]
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'rate' => 'Rate',
            'description' => 'Комментарий',
            'price' => 'Ваша цена'
        ];
    }

    public function getUser()
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }
}
