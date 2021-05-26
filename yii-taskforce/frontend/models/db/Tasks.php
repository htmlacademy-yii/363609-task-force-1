<?php

namespace frontend\models\db;

use Yii;

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
            [['dt_add', 'expire'], 'safe'],
            [['category_id', 'budget'], 'integer'],
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
}
