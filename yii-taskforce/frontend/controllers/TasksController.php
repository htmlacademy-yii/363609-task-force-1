<?php
namespace frontend\controllers;

use Yii;
use frontend\models\form\TasksForm;

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

}
