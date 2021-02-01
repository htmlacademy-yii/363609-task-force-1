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
