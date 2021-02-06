<?php

namespace Razot\controller;

class CancelAction extends Action
{
    public function getCode()
    {
        return 'cancel';
    }

    public function getName()
    {
        return 'Отменить';
    }

    public static function checkPermission($task, $currentUserId)
    {
        return $task->getCustomer() == $currentUserId && $task->getCurrentStatus() == $task::STATUS_NEW;
    }

}
