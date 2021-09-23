<?php
namespace frontend\models\helpers;

use frontend\models\interface\ActionInterface;

class DoneAction implements ActionInterface
{
    /**
     * @param \frontend\models\db\Tasks $task
     * @param int $currentUserId
     * @return bool
     */
    public static function checkPermission($task, $currentUserId)
    {
        return $task->getCustomerId() == $currentUserId && $task->status == $task::STATUS_IN_WORK;
    }
}
