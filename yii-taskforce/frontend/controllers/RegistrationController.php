<?php

namespace frontend\controllers;

use frontend\models\form\RegistrationForm;
use Yii;
use yii\web\Controller;
use yii\web\Response;

class RegistrationController extends Controller
{
    /**
     * @return string|Response
     */
    public function actionIndex()
    {
        $model = new RegistrationForm();
        $errors = [];

        if ($model->load(Yii::$app->request->post())) {
            if ($model->validate()) {
                $user = $model->register();
                if (!$user->hasErrors()) {
                    Yii::$app->user->login($user);
                    return $this->redirect(['site/index']);
                }
            }
            $errors = $model->getErrors();
        }

        return $this->render('index', [
            'model' => $model,
            'errors' => $errors
        ]);
    }

}
