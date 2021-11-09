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

    public function getCategoriesList()
    {
        $arCategories = Categories::find()
            ->select(['id', 'name'])
            ->all();
        return ArrayHelper::map($arCategories, 'id', 'name');
    }

    public function getDataProvider()
    {
        if ($sort = Yii::$app->request->get('sort')) {
            $sortDirection = substr($sort, 0, 1) === '-' ? 'SORT_DESC' : 'SORT_ASC';

            $query = User::find()
                ->alias('u')
                ->where(['u.id' => Yii::$app->authManager->getUserIdsByRole('executor')])
                ->select(['u.*', 'COUNT(opinions.id)', 'COUNT(tasks.id)'])
                ->with('profile')
                ->joinWith([
                    'categories',
                    'tasksExecutor' => function (ActiveQuery $query) use ($sortDirection) {
                        $query->orderBy(['COUNT(tasks.id)' => $sortDirection]);
                    },
                    'opinions' => function (ActiveQuery $query) use ($sortDirection) {
                        $query->orderBy(['COUNT(opinions.id)' => $sortDirection]);
                    },
                    'favorites',
                ]);
        } else {

            $query = User::find()
                ->alias('u')
                ->where(['u.id' => Yii::$app->authManager->getUserIdsByRole('executor')])
                ->select(['u.*'])
                ->with('profile')
                ->joinWith([
                    'categories',
                    'favorites',
                    'tasksExecutor',
//                    'tasksExecutor' => function (ActiveQuery $query) {
//                       // $query->orderBy(['COUNT(tasks.id)' => 'SORT_DESC']);
//                    },
                    'opinions'
//                    'opinions' => function (ActiveQuery $query) {
//                       // $query->orderBy(['COUNT(opinions.id)' => 'SORT_DESC']);
//                    }
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
                    ->asArray()
                    ->all();

                $query->andWhere(['u.id' => $idFavorites]);
            }
            if (!empty($this->online)) {
                $query->andWhere('u.last_activity > TIMESTAMP(NOW() - INTERVAL :period MINUTE)', ['period' => 30]);
            }

            $query->andFilterWhere(['users_categories.category_id' => $this->categories]);
            $query->andFilterWhere([
                'like', 'users.name', $this->name,
            ]);
        }

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
                        'asc' => ['opinions.rate' => SORT_DESC],
                        'desc' => ['opinions.rate' => SORT_DESC],
                        'default' => SORT_DESC
                    ],
                    'tasks' => [
                        'asc' => ['COUNT(tasks.id)' => SORT_ASC],
                        'desc' => ['COUNT(tasks.id)' => SORT_DESC],
                        'default' => SORT_DESC
                    ],
                    'review' => [
                        'asc' => ['COUNT(opinions.user_id)' => SORT_ASC],
                        'desc' => ['COUNT(opinions.user_id)' => SORT_DESC],
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
