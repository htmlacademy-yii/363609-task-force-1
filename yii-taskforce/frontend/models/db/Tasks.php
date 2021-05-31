<?php

namespace frontend\models\db;

use Yii;
use frontend\models\db\Categories;

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
 * @property float|null $lat
 * @property float|null $long
 */
class Tasks extends \yii\db\ActiveRecord
{

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
            [['category_id', 'budget', 'status'], 'integer'],
            [['description', 'address'], 'string'],
            [['lat', 'long'], 'number'],
            [['name'], 'string', 'max' => 255],
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
            'category_id' => 'Category ID',
            'description' => 'Description',
            'expire' => 'Expire',
            'name' => 'Name',
            'address' => 'Address',
            'budget' => 'Budget',
            'lat' => 'Lat',
            'long' => 'Long',
        ];
    }

    public function getCategories()
    {
        return $this->hasOne(Categories::class, ['id' => 'category_id']);
    }
}
