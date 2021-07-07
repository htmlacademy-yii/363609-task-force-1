<?php
namespace frontend\controllers;

use Yii;
use frontend\models\form\TasksForm;

class TasksController extends \yii\web\Controller
{
    public function actionIndex()
    {
        $post = Yii::$app->request->post();
        $model = new TasksForm();
        $model->load($post);

        $dataProvider = $model->getDataProvider();
        $dataProvider->setTotalCount($dataProvider->getCount());
        $dataProvider->setPagination(['totalCount' => $dataProvider->getCount()]);

        return $this->render('index',
            [
                'model' => $model,
                'post' => $post['TasksForm']??'',
                'dataProvider' => $dataProvider
            ]);
    }

}
