<?php

namespace frontend\models\interface;

use frontend\models\db\Tasks;

/**
 * интерфейс для действий с заданиями
 */
interface ActionInterface
{
    /**
     * Проверяет доступность действия для задания и юзера
     *
     * @param Tasks $task
     * @param int $currentUserId
     * @return bool
     */
    public static function checkPermission(Tasks $task, int $currentUserId): bool;

    /**
     * Возвращает название действия
     *
     * @return string
     */
    public static function getAction(): string;
}
