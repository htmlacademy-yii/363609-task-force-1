<?php

namespace Razot\controller;

abstract class Action
{
    abstract public function getName();

    abstract public function getCode();

    abstract public static function checkPermission(Task $task, int $currentUserId);
}
