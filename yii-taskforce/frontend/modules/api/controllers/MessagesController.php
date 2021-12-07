<?php
namespace frontend\modules\api\controllers;

use frontend\models\db\Messages;
use Yii;
use yii\filters\AccessControl;
use yii\rest\ActiveController;

class MessagesController extends ActiveController
{
    public $modelClass = Messages::class;

    /**
     * Проверка авторизации на сайте
     *
     * @return array|array[]
     */
    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['access'] = [
            'class' => AccessControl::class,
            'denyCallback' => function ($rule, $action) {
                $this->redirect('/');
            },
            'rules' => [
                [
                    'allow' => true,
                    'roles' => ['@']
                ]
            ]
        ];
        return $behaviors;
    }

    /**
     * Удалим дефолтный index, чтобы доставать сообщения только для нужного задания
     *
     * @return array
     */
    public function actions()
    {
        $actions = parent::actions();
        unset($actions['index']);
        return $actions;
    }

    /**
     * @param $task_id
     * @return Messages[]
     */
    public function actionIndex($task_id)
    {
        return Messages::findAll(['task_id' => $task_id]);
    }

}
