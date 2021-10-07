<?php
namespace frontend\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\AccessControl;

class SecuredController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'denyCallback' => function ($rule, $action) {
                    $this->redirect(['site/index']);
                },
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@']
                    ]
                ]
            ]
        ];
    }

    public function beforeAction($action)
    {
        Yii::$app->user->identity->updateAttributes(['last_activity' => new \yii\db\Expression('NOW()')]);
        return parent::beforeAction($action);
    }
}
