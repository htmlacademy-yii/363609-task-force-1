<?php

namespace frontend\models\form;

use common\models\User;
use frontend\models\db\Categories;
use frontend\models\db\UserFavorites;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use yii\db\ActiveQuery;
use yii\helpers\ArrayHelper;

class UsersForm extends Model
{
    public $categories;
    public $name;
    public $free;
    public $online;
    public $haveReviews;
    public $favorites;

    public function rules()
    {
        return [
            [['categories'], 'safe'],
            [['name'], 'string'],
            [['free', 'online', 'haveReviews', 'favorites'], 'integer']
        ];
    }

    public function attributeLabels()
    {
        return [
            'categories' => 'Категории',
            'additionals' => 'Дополнительно',
            'name' => 'Поиск по названию',
            'free' => 'Сейчас свободен',
            'online' => 'Сейчас онлайн',
            'haveReviews' => 'Есть отзывы',
            'favorites' => 'В избранном',
        ];
    }

    /**
     * @return array
     */
    public function getCategoriesList()
    {
        $arCategories = Categories::find()
            ->select(['id', 'name'])
            ->all();
        return ArrayHelper::map($arCategories, 'id', 'name');
    }

    /**
     * @return ActiveDataProvider
     */
    public function getDataProvider()
    {
        $query = User::find()
            ->alias('u')
            ->where(['u.id' => Yii::$app->authManager->getUserIdsByRole('executor')])
            ->select(['u.*', 'AVG(opinions.rate)'])
            ->joinWith([
                'categories',
                'favorites',
                'tasksExecutor',
                'opinions'
            ]);

        if (!empty($this->free)) {
            $query->andWhere('u.id NOT IN (SELECT executor_id FROM tasks)');
        }
        if (!empty($this->haveReviews)) {
            $query->andWhere('u.id IN (SELECT user_id FROM opinions)');
        }
        if (!empty($this->favorites)) {
            $idFavorites = UserFavorites::find()
                ->where(['user_id' => Yii::$app->user->identity->id])
                ->select(['favorite_id'])
                ->column();

            $query->andWhere(['u.id' => $idFavorites]);
        }
        if (!empty($this->online)) {
            $query->andWhere('u.last_activity > TIMESTAMP(NOW() - INTERVAL :period MINUTE)', ['period' => 30]);
        }

        $query->andFilterWhere(['users_categories.category_id' => $this->categories]);
        $query->andFilterWhere([
            'like', 'users.name', $this->name,
        ]);

        $query->groupBy(['u.id']);

        return new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => ['create' => SORT_DESC],
                'attributes' => [
                    'create' => [
                        'asc' => ['u.created_at' => SORT_ASC],
                        'desc' => ['u.created_at' => SORT_DESC],
                        'default' => SORT_DESC
                    ],
                    'rating' => [
                        'asc' => ['AVG(opinions.rate)' => SORT_ASC],
                        'desc' => ['AVG(opinions.rate)' => SORT_DESC],
                        'default' => SORT_DESC
                    ],
                    'tasks' => [
                        'asc' => ['COUNT(DISTINCT(tasks.id))' => SORT_ASC],
                        'desc' => ['COUNT(DISTINCT(tasks.id))' => SORT_DESC],
                        'default' => SORT_DESC
                    ],
                    'review' => [
                        'asc' => ['COUNT(DISTINCT(opinions.id))' => SORT_ASC],
                        'desc' => ['COUNT(DISTINCT(opinions.id))' => SORT_DESC],
                        'default' => SORT_DESC
                    ]
                ],
            ],
            'pagination' => [
                'pageSize' => 5
            ]
        ]);
    }

}
