<?php
namespace frontend\models\helpers;

use frontend\models\interface\ActionInterface;
use \frontend\models\db\Tasks;

class CancelAction implements ActionInterface
{
    /**
     * @param \frontend\models\db\Tasks $task
     * @param int $currentUserId
     * @return bool
     */
    public static function checkPermission(Tasks $task, int $currentUserId): bool
    {
        return $task->getCustomerId() == $currentUserId && $task->status == $task::STATUS_NEW;
    }

    /**
     * @param \frontend\models\db\Tasks $task
     * @return string
     */
    public static function getAction(Tasks $task): string
    {
        return $task::ACTION_CANCEL;
    }
}
