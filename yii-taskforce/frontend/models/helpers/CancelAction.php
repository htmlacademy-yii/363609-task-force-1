<?php

namespace frontend\models\helpers;

use frontend\models\db\Tasks;
use frontend\models\interface\ActionInterface;

class CancelAction implements ActionInterface
{
    /**
     * @param Tasks $task
     * @param int $currentUserId
     * @return bool
     */
    public static function checkPermission(Tasks $task, int $currentUserId): bool
    {
        return $task->getCustomerId() == $currentUserId && $task->status == $task::STATUS_NEW;
    }

    /**
     * @return string
     */
    public static function getAction(): string
    {
        return Tasks::ACTION_CANCEL;
    }
}
