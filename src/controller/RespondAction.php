<?php

namespace Razot\controller;

class RespondAction extends Action
{
    public function getCode()
    {
        return 'respond';
    }

    public function getName()
    {
        return 'Откликнуться';
    }

    public static function checkPermission($task, $currentUserId)
    {
        return $task->getExecutor() != $currentUserId && $task->getCurrentStatus() == $task::STATUS_NEW;
    }
}
