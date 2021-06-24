<?php

namespace frontend\models\db;

use Yii;

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
            [['dt_add'], 'safe'],
            [['rate', 'user_id', 'task_id', 'accept'], 'integer'],
            [['description'], 'string'],
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
            'rate' => 'Rate',
            'description' => 'Description',
        ];
    }
}
