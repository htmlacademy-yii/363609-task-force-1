<?php
namespace frontend\models\helpers;

use frontend\models\interface\ActionInterface;

class RespondAction implements ActionInterface
{
    /**
     * @param \frontend\models\db\Tasks $task
     * @param int $currentUserId
     * @return bool
     */
    public static function checkPermission($task, $currentUserId)
    {
        return $task->getExecutorId() != $currentUserId && $task->status == $task::STATUS_NEW;
    }
}
