<?php

namespace frontend\models\db;

use Yii;

/**
 * This is the model class for table "user_files".
 *
 * @property int $id
 * @property int|null $id_user
 * @property string|null $name
 * @property string|null $path
 * @property string|null $absolute_path
 * @property int|null $created_at
 * @property int|null $updated_at
 */
class UserFiles extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'user_files';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_user', 'created_at', 'updated_at'], 'integer'],
            [['name', 'path', 'absolute_path'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'id_user' => 'Id User',
            'name' => 'Name',
            'path' => 'Path',
            'absolute_path' => 'Absolute Path',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }
}
