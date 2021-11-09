<?php

namespace frontend\models\helpers;

use frontend\models\db\Tasks;
use frontend\models\interface\ActionInterface;

class RespondAction implements ActionInterface
{
    /**
     * @param Tasks $task
     * @param int $currentUserId
     * @return bool
     */
    public static function checkPermission(Tasks $task, int $currentUserId): bool
    {
        return $task->getExecutorId() != $currentUserId && $task->status == $task::STATUS_NEW;
    }

    /**
     * @return string
     */
    public static function getAction(): string
    {
        return Tasks::ACTION_RESPOND;
    }
}
