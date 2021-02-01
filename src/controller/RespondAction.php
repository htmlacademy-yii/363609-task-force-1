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

    public static function checkPermission($executorId, $customerId, $currentUserId)
    {
        if($executorId != $currentUserId) {
            return true;
        }
        else {
            return false;
        }
    }
}
