<?php
namespace frontend\models\db;

use frontend\models\form\UsersForm;
use Yii;
use common\models\User;
use yii\data\ActiveDataProvider;

class UsersSearch extends Tasks
{
    public $additional;

    public function search($params)
    {
        $query = User::find()->where(['users.id' => Yii::$app->authManager->getUserIdsByRole('executor')])->with('profile')->joinWith(['categories', 'opinions', 'tasksExecutor', 'favorites']);

        $dataProvider = new ActiveDataProvider([
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

        $this->load($params);

        if(!$this->validate()) {
            return $dataProvider;
        }

        $query->andFilterWhere(['users_categories.category_id' => $params['categories']??'']);
        $query->andFilterWhere([
                'like', 'users.name', $params['name']??'',
            ]);

        if(!empty($params['additionals'])) {

            $ar = [];

            if(in_array(UsersForm::FREE, $params['additionals'])) {
                $ar[] = 'users.id NOT IN (SELECT executor_id FROM tasks)';
            }
            if(in_array(UsersForm::HAVE_REVIEWS, $params['additionals'])) {
                $ar[] = 'users.id IN (SELECT user_id FROM opinions)';
            }
            if(in_array(UsersForm::FAVORITES, $params['additionals'])) {
                $ar[] = 'users.id IN (SELECT user_id FROM user_favorites)';
            }

            $query->andWhere(implode(' OR ', $ar));

        }

        return $dataProvider;

    }

}
