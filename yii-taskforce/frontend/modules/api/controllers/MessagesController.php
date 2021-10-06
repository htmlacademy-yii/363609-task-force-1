<?php
namespace frontend\modules\api\controllers;

use frontend\models\db\Messages;
use yii\rest\ActiveController;
use Yii;
use yii\helpers\Json;
use yii\filters\AccessControl;

class MessagesController extends ActiveController
{
    public $modelClass = Messages::class;

}
