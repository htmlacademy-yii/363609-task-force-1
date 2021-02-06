<?php

namespace Razot\controller;

class DoneAction extends Action
{
    public function getCode()
    {
        return 'done';
    }

    public function getName()
    {
        return 'Выполнено';
    }

    public static function checkPermission($task, $currentUserId)
    {
        return $task->getCustomer() == $currentUserId && $task->getCurrentStatus() == $task::STATUS_IN_WORK;
    }
}
