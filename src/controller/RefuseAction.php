<?php

namespace Razot\controller;

class RefuseAction extends Action
{
    public function getCode()
    {
        return 'refuse';
    }

    public function getName()
    {
        return 'Отказаться';
    }

    public static function checkPermission($task, $currentUserId)
    {
        return $task->getExecutor() == $currentUserId && $task->getCurrentStatus() == $task::STATUS_IN_WORK;
    }
}
