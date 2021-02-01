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

    public static function checkPermission($executorId, $customerId, $currentUserId)
    {
        if($customerId == $currentUserId) {
            return true;
        }
        else {
            return false;
        }
    }
}
