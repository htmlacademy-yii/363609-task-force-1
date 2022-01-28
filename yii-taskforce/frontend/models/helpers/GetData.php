<?php

namespace frontend\models\helpers;

use frontend\models\db\Cities;
use frontend\models\db\Notice;
use Yii;

class GetData
{
    /**
     * @return array|Notice[]
     */
    public static function getNotice()
    {
        return Notice::find()
            ->with('task')
            ->where(['user_id' => Yii::$app->user->identity->id, 'is_read' => 0])
            ->limit(10)
            ->all();
    }

    /**
     * @return array|Cities[]
     */
    public static function getCity()
    {
        return Cities::find()
            ->select(['id', 'city'])
            ->all();
    }


}
