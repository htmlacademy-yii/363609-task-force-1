<?php

namespace frontend\models\helpers;

use frontend\models\db\Notice;
use Yii;

class GetData
{
    public static function getNotice()
    {
        return Notice::find()
            ->with('task')
            ->where(['user_id' => Yii::$app->user->identity->id, 'is_read' => 0])
            ->limit(10)
            ->all();
    }
}
