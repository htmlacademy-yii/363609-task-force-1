<?php

namespace frontend\controllers;

use common\models\User;
use DateTime;
use frontend\models\db\Tasks;
use frontend\models\form\UsersForm;
use Yii;

class UsersController extends SecuredController
{
    /**
     * @return string
     */
    public function actionIndex()
    {
        $get = Yii::$app->request->get();
        $model = new UsersForm();
        $model->load($get);

        return $this->render('index',
            [
                'model' => $model,
                'get' => $get['UsersForm'] ?? '',
                'dataProvider' => $model->getDataProvider()
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

        return $this->render('view',
            [
                'model' => $model,
                'completedTasks' => $completedTasks,
                'age' => $age ?? null
            ]
        );
    }

}
