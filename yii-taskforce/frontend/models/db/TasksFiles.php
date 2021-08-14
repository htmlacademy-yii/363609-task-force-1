<?php

namespace frontend\models\db;

use Yii;

/**
 * This is the model class for table "tasks_files".
 *
 * @property int $id
 * @property int|null $id_task
 * @property string|null $path
 * @property string|null $absolute_path
 * @property int|null $created_at
 * @property int|null $updated_at
 */
class TasksFiles extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'tasks_files';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_task', 'created_at', 'updated_at'], 'integer'],
            [['path', 'absolute_path'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'id_task' => 'Id Task',
            'path' => 'Path',
            'absolute_path' => 'Absolute Path',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }
}
