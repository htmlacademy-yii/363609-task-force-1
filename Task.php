<?php

class Task
{
    const STATUS_NEW = 'new';
    const STATUS_CANCELED = 'canceled';
    const STATUS_IN_WORK = 'in_work';
    const STATUS_COMPLETED = 'completed';
    const STATUS_FAILED = 'failed';

    const ACTION_CANCEL = 'cancel';
    const ACTION_RESPOND = 'respond';
    const ACTION_DONE = 'done';
    const ACTION_REFUSE = 'refuse';

    private $executorId;
    private $customerId;

    public function __construct($executor, $customer)
    {
        $this->executorId = $executor;
        $this->customerId = $customer;
    }

    public function getAllStatus()
    {
        $arStatus = [
          self::STATUS_NEW => 'Новое',
          self::STATUS_CANCELED => 'Отменено',
          self::STATUS_IN_WORK => 'В работе',
          self::STATUS_COMPLETED => 'Выполнено',
          self::STATUS_FAILED => 'Провалено',
        ];

        return $arStatus;
    }

    public function getAllActions()
    {
        $arActions = [
            self::ACTION_CANCEL => 'Отменить',
            self::ACTION_RESPOND => 'Откликнуться',
            self::ACTION_DONE => 'Выполнено',
            self::ACTION_REFUSE => 'Отказаться',
        ];

        return $arActions;
    }

    public function getAvailAction($status)
    {
        switch ($status) {

            case self::STATUS_NEW:
                $arAction = [
                    'customer' => self::ACTION_CANCEL,
                    'executor' => self::ACTION_RESPOND,
                ];
                break;

            case self::STATUS_IN_WORK:
                $arAction = [
                    'customer' => self::ACTION_DONE,
                    'executor' => self::ACTION_REFUSE,
                ];
                break;

            default:
                $arAction = 'no actions available';

        }

        return $arAction;
    }

    public function getNextStatus($action, $current_status)
    {
        switch ($action) {

            case self::ACTION_CANCEL:

                if($current_status == self::STATUS_NEW) {
                    $nextStatus = self::STATUS_CANCELED;
                }
                break;

            case self::ACTION_RESPOND:

                if($current_status == self::STATUS_NEW) {
                    $nextStatus = self::STATUS_IN_WORK;
                }
                break;

            case self::ACTION_DONE:

                if($current_status == self::STATUS_IN_WORK) {
                    $nextStatus = self::STATUS_COMPLETED;
                }
                break;

            case self::ACTION_REFUSE:

                if($current_status == self::STATUS_IN_WORK) {
                    $nextStatus = self::STATUS_FAILED;
                }
                break;

        }
        if(empty($nextStatus)) {
            $nextStatus = 'no statuses available';
        }

        return $nextStatus;
    }
}
