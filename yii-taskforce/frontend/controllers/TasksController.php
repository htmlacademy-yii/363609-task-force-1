<?php
namespace frontend\controllers;

use frontend\models\db\Tasks;
use Yii;
use frontend\models\form\TasksForm;
use yii\web\NotFoundHttpException;

class TasksController extends \yii\web\Controller
{
    public function actionIndex()
    {
        $get = Yii::$app->request->get();
        $model = new TasksForm();
        $model->load($get);

        return $this->render('index',
            [
                'model' => $model,
                'get' => $get['TasksForm']??'',
                'dataProvider' => $model->getDataProvider()
            ]);
    }

    public function actionView($id)
    {
        $model = Tasks::findOne($id);
        if(empty($model)) {
            throw new NotFoundHttpException('Задание не найдено');
        }

        $now = new \DateTime(); // текущее время на сервере
        $date = \DateTime::createFromFormat("Y-m-d", $model->customer->dt_add); // задаем дату в любом формате
        $interval = $now->diff($date); // получаем разницу в виде объекта DateInterval

        $replies = $model->replies;

        return $this->render('view',
            [
                'model' => $model,
                'interval' => $interval,
                'replies' => $replies
            ]
        );
    }

}
