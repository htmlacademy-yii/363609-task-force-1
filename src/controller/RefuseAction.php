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

    public static function checkPermission($executorId, $customerId, $currentUserId)
    {
        if($executorId == $currentUserId) {
            return true;
        }
        else {
            return false;
        }
    }
}
