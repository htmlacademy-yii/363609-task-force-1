<?php
namespace frontend\models\interface;

use frontend\models\db\Tasks;

/**
 * интерфейс для действий с заданиями
 */
interface ActionInterface
{
    public static function checkPermission(Tasks $task, int $currentUserId);
}
