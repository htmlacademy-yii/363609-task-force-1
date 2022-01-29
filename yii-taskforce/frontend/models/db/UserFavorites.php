<?php

namespace frontend\models\db;

use yii\db\ActiveQuery;
use yii\db\ActiveRecord;
use common\models\User;

/**
 * This is the model class for table "user_favorites".
 *
 * @property int $id
 * @property int $user_id
 * @property int $favorite_id
 *
 * @property User $favorite
 * @property User $user
 */
class UserFavorites extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'user_favorites';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id', 'favorite_id'], 'required'],
            [['user_id', 'favorite_id'], 'integer'],
            [['favorite_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['favorite_id' => 'id']],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['user_id' => 'id']],
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
            'favorite_id' => 'Favorite ID',
        ];
    }

    /**
     * Gets query for [[Favorite]].
     *
     * @return ActiveQuery
     */
    public function getFavorite()
    {
        return $this->hasOne(User::class, ['id' => 'favorite_id']);
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
}
