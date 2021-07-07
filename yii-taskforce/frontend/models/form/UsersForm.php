<?php
namespace frontend\models\form;

use common\models\User;
use yii\base\Model;
use Yii;
use yii\helpers\ArrayHelper;
use yii\data\ActiveDataProvider;
use frontend\models\db\Categories;

class UsersForm extends Model
{
    public const FREE = 1;
    public const ONLINE = 2;
    public const HAVE_REVIEWS = 3;
    public const FAVORITES = 4;
    public const AR_ADDITIONALS = [
        self::FREE => 'Сейчас свободен',
        self::ONLINE => 'Сейчас онлайн',
        self::HAVE_REVIEWS => 'Есть отзывы',
        self::FAVORITES => 'В избранном'
    ];

    public $categories;
    public $additionals;
    public $name;

    public function rules()
    {
        return [
            [['categories', 'additionals'], 'safe'],
            [['name'], 'string'],
        ];
    }

    public function attributeLabels() {
        return [
            'categories' => 'Категории',
            'additionals' => 'Дополнительно',
            'name' => 'Поиск по названию',
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
        $query = User::find()->where(['users.id' => Yii::$app->authManager->getUserIdsByRole('executor')])->with('profile')->joinWith(['categories', 'opinions', 'tasksExecutor', 'favorites']);

        $query->andFilterWhere(['users_categories.category_id' => $this->categories]);
        $query->andFilterWhere([
            'like', 'users.name', $this->name,
        ]);

        if(!empty($this->additionals)) {

            $ar = [];

            if(in_array(UsersForm::FREE, $this->additionals)) {
                $ar[] = 'users.id NOT IN (SELECT executor_id FROM tasks)';
            }
            if(in_array(UsersForm::HAVE_REVIEWS, $this->additionals)) {
                $ar[] = 'users.id IN (SELECT user_id FROM opinions)';
            }
            if(in_array(UsersForm::FAVORITES, $this->additionals)) {
                $ar[] = 'users.id IN (SELECT user_id FROM user_favorites)';
            }

            $query->andWhere(implode(' OR ', $ar));

        }

        return new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => ['create' => SORT_DESC],
                'attributes' => [
                    'create' => [
                        'asc' => ['users.created_at' => SORT_ASC],
                        'desc' => ['users.created_at' => SORT_DESC],
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
