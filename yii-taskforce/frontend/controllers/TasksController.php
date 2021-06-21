<?php
namespace frontend\controllers;

use frontend\models\db\Tasks;

class TasksController extends \yii\web\Controller
{
    public function actionIndex()
    {
        $model = Tasks::find()
            ->where(['status' => Tasks::STATUS_NEW])
            ->with('categories')
            ->orderBy('id DESC')
            ->all();

        return $this->render('index', compact('model'));
    }

}
