<?php

namespace frontend\controllers;

use frontend\models\db\Notice;
use Yii;
use yii\web\Controller;

class EventsController extends Controller
{
    public function actionIndex()
    {
        Notice::updateAll(['is_read' => 1], ['user_id' => Yii::$app->user->identity->id]);
    }

}
