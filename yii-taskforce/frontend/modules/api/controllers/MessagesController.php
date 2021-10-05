<?php
namespace frontend\modules\api\controllers;

use frontend\models\db\Messages;
use yii\rest\ActiveController;
use Yii;
use yii\helpers\Json;

class MessagesController extends ActiveController
{
    public $modelClass = Messages::class;

    public function actions(){

        $actions = parent::actions();
        unset($actions['create']);
        unset($actions['index']);
        return $actions;
    }

    public function actionCreate(){

        $model = new Messages();
        $post_data = Yii::$app->getRequest()->getBodyParams();
        $model->load($post_data, '');
        try {
            if($model->save()) {
                $response = Yii::$app->getResponse();
                $response->setStatusCode(201);
                return [
                    'id' => $model->id,
                    'message' => $model->message,
                    'published_at' => Yii::$app->formatter->asDatetime($model->timestamp, 'php:Y-m-d H:i:s'),
                    'is_mine' => $model->is_mine
                ];
            }
        }
        catch (\Throwable $e) {
            $response = Yii::$app->getResponse();
            $response->setStatusCode(500);
            return $e->getMessage();
        }
    }

    public function actionIndex($task_id)
    {
        $models = Messages::findAll(['task_id' => $task_id]);

        $arReturn = [];

        foreach ($models as $model) {
            $arReturn[] = [
                'id' => $model->id,
                'message' => $model->message,
                'published_at' => Yii::$app->formatter->asDatetime($model->timestamp, 'php:Y-m-d H:i:s'),
                'is_mine' => $model->is_mine
            ];
        }

        return $arReturn;
    }
}
