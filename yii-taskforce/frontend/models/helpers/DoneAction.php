<?php

namespace frontend\models\helpers;

use frontend\models\db\Tasks;
use frontend\models\interface\ActionInterface;

class DoneAction implements ActionInterface
{
    /**
     * @param Tasks $task
     * @param int $currentUserId
     * @return bool
     */
    public static function checkPermission(Tasks $task, int $currentUserId): bool
    {
        return $task->getCustomerId() == $currentUserId && $task->status == $task::STATUS_IN_WORK;
    }

    /**
     * @return string
     */
    public static function getAction(): string
    {
        return Tasks::ACTION_DONE;
    }
}
