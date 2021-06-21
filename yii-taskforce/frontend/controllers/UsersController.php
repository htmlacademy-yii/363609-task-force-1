<?php

namespace frontend\controllers;
use Yii;
use common\models\User;

class UsersController extends \yii\web\Controller
{
    public function actionIndex()
    {
        $modelUser = User::find()
            ->where(['id' => Yii::$app->authManager->getUserIdsByRole('executor')])
            ->with('profile', 'opinions')
            ->orderBy('id DESC')
            ->all();

        return $this->render('index', compact('modelUser'));
    }

}
