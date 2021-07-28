<?php
namespace frontend\controllers;

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

}
