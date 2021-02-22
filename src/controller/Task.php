<?php

namespace Razot\controller;
use Razot\ex\StatusException;

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

    const STATUSES_LIST = [
        self::STATUS_NEW => 'Новое',
        self::STATUS_CANCELED => 'Отменено',
        self::STATUS_IN_WORK => 'В работе',
        self::STATUS_COMPLETED => 'Выполнено',
        self::STATUS_FAILED => 'Провалено',
    ];

    const ACTIONS_LIST = [
        self::ACTION_CANCEL => 'Отменить',
        self::ACTION_RESPOND => 'Откликнуться',
        self::ACTION_DONE => 'Выполнено',
        self::ACTION_REFUSE => 'Отказаться',
    ];

//    const AVAILABLE_ACTIONS_MAP = [
//        self::STATUS_NEW => [
//            self::ACTION_CANCEL,
//            self::ACTION_RESPOND
//        ],
//        self::STATUS_IN_WORK => [
//            self::ACTION_DONE,
//            self::ACTION_REFUSE
//        ],
//    ];

    private const AVAILABLE_ACTIONS_MAP = [
        CancelAction::class,
        DoneAction::class,
        RefuseAction::class,
        RespondAction::class
    ];

    private $executorId;
    private $customerId;
    private $currentUser;
    //private $currentStatus = self::STATUS_NEW;
    private $currentStatus;

    public function __construct(int $executor, int $customer, string $currentStatus = self::STATUS_NEW)
    {
        $this->executorId = $executor;
        $this->customerId = $customer;
        //id авторизованного юзера
        $this->currentUser = 1;
        if(!self::STATUSES_LIST[$currentStatus]) {
            throw new StatusException('Данный статус не существует');
        }
        $this->currentStatus = $currentStatus;
    }

   public function getExecutor() :int
   {
       return $this->executorId;
   }

    public function getCustomer() :int
    {
        return $this->customerId;
    }

    public function getCurrentStatus() :string
    {
        return $this->currentStatus;
    }

    public function getAvailableActions() :array
    {
        $currentUser = $this->currentUser;

        return array_filter(self::AVAILABLE_ACTIONS_MAP, function ($action) use ($currentUser){
            return call_user_func([$action, 'checkPermission'], $this, $currentUser);
        });
    }

    public function getNextStatus($action) :?string
    {
        switch (true) {

            case $action === self::ACTION_CANCEL && $this->currentStatus === self::STATUS_NEW:

                return self::STATUS_CANCELED;
                break;

            case $action === self::ACTION_RESPOND && $this->currentStatus == self::STATUS_NEW:

                return self::STATUS_IN_WORK;
                break;

            case $action === self::ACTION_DONE && $this->currentStatus == self::STATUS_IN_WORK:

                return self::STATUS_COMPLETED;
                break;

            case $action === self::ACTION_REFUSE && $this->currentStatus == self::STATUS_IN_WORK:

                return self::STATUS_FAILED;
                break;

            default:
                return null;

        }
    }
}
