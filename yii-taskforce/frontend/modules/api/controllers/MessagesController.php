<?php
namespace frontend\modules\api\controllers;

use frontend\models\db\Tasks;
use yii\rest\ActiveController;
use yii\rest\Controller;

class MessagesController extends ActiveController
{
    public $modelClass = Tasks::class;
}
