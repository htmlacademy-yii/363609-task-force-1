<?php
namespace frontend\controllers;

use common\models\User;
use frontend\models\db\Tasks;
use Yii;
use frontend\models\form\UsersForm;

class UsersController extends \yii\web\Controller
{
    public function actionIndex()
    {
        $get = Yii::$app->request->get();
        $model = new UsersForm();
        $model->load($get);

        return $this->render('index',
            [
                'model' => $model,
                'get' => $get['UsersForm']??'',
                'dataProvider' => $model->getDataProvider()
            ]);
    }

    public function actionView($id)
    {
        $model = $this->findModel($id);

        $reviews = $model->opinions;

        $completedTasks = Tasks::find()->where(['status' => Tasks::STATUS_COMPLETED, 'executor_id' => $model->id])->count();

        $now = new \DateTime(); // текущее время на сервере
        $date = \DateTime::createFromFormat("Y-m-d", $model->profile->bd); // задаем дату в любом формате
        $interval = $now->diff($date); // получаем разницу в виде объекта DateInterval

        return $this->render('view',
            [
                'model' => $model,
                'reviews' => $reviews,
                'completedTasks' => $completedTasks,
                'interval' => $interval
            ]
        );
    }

    protected function findModel($id)
    {
        $model = User::findOne($id);
        if(empty($model)) {
            throw new NotFoundHttpException('Пользователь не найдено');
        }
        return $model;
    }

}
