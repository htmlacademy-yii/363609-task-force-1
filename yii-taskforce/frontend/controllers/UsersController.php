<?php

namespace frontend\controllers;

use common\models\User;
use DateTime;
use frontend\models\db\Tasks;
use frontend\models\db\UserFavorites;
use frontend\models\form\UsersForm;
use Yii;
use yii\web\NotFoundHttpException;

class UsersController extends SecuredController
{
    /**
     * @return string
     */
    public function actionIndex()
    {
        $model = new UsersForm();

        return $this->render('index',
            [
                'model' => $model,
                'dataProvider' => $model->search(Yii::$app->request->get()),
                'sort' => Yii::$app->request->get('sort', '-rating')
            ]);
    }

    /**
     * @param $id
     * @return string
     */
    public function actionView($id)
    {
        $model = User::findOne($id);
        if (empty($model)) {
            throw new NotFoundHttpException('Пользователь не найдено');
        }

        $completedTasks = Tasks::find()->where(['status' => Tasks::STATUS_COMPLETED, 'executor_id' => $model->id])->count();

        if($model->birthday) {
            $now = new DateTime(); // текущее время на сервере
            $date = DateTime::createFromFormat("Y-m-d", $model->birthday); // задаем дату в любом формате
            $interval = $now->diff($date); // получаем разницу в виде объекта DateInterval
            $age = $interval->y;
        }

        $favorite = UserFavorites::find()
            ->where(
                [
                    'user_id' => Yii::$app->user->identity->id,
                    'favorite_id' => $model->id
                ]
            )
            ->count();

        return $this->render('view',
            [
                'model' => $model,
                'completedTasks' => $completedTasks,
                'age' => $age ?? null,
                'favorite' => $favorite
            ]
        );
    }

    /**
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     */
    public function actionAddFavorite()
    {
        $post = Yii::$app->request->post();

        if (!empty($post) && !empty($post['user'])) {
            $userId = Yii::$app->user->identity->id;
            $model = UserFavorites::find()->where(['user_id' => $userId, 'favorite_id' => $post['user']])->one();
            if (empty($model)) {
                $model = new UserFavorites();
                $model->setAttributes([
                    'user_id' => $userId,
                    'favorite_id' => $post['user']
                ]);
                if ($model->validate()) {
                    return $this->asJson(['success' => $model->save()]);
                }
            } else {
                return $this->asJson(['success' => (bool)$model->delete()]);
            }
        }
    }

}
