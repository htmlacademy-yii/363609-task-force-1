<?php

namespace src\model;

use common\models\User;
use frontend\models\db\Notice;
use frontend\models\db\Tasks;
use Yii;
use yii\helpers\Html;

class NoticeModel
{
    /**
     * @param $taskId
     */
    public static function addNewRespond($taskId)
    {
        $task = Tasks::find()
            ->where(['id' => $taskId])
            ->select(['customer_id', 'id'])
            ->one();

        $user = User::findOne(['id' => $task->customer_id]);

        if(!$user) {
            return;
        }

        if($user->setting_action_task) {
            $model = new Notice();
            $model->setAttributes([
                'user_id' => $task->customer_id,
                'task_id' => $task->id,
                'type' => Notice::TYPE_NEW_RESPOND,
                'title' => Notice::TYPE_MAP[Notice::TYPE_NEW_RESPOND]['title'],
                'text' => str_replace(['#TASK#', '#LINK#'], [$task->name, Html::a('ссылке', ['tasks/view', 'id' => $task->id])], Notice::TYPE_MAP[Notice::TYPE_NEW_RESPOND]['text']),
            ]);
            if($model->save()) {
                self::send($user->email, $model->title, $model->text);
            }
        }
    }

    /**
     * @param $taskId
     */
    public static function addTaskRefuse($taskId)
    {
        $task = Tasks::find()
            ->where(['id' => $taskId])
            ->select(['customer_id', 'id'])
            ->one();

        $user = User::findOne(['id' => $task->customer_id]);

        if(!$user) {
            return;
        }

        if($user->setting_action_task) {
            $model = new Notice();
            $model->setAttributes([
                'user_id' => $task->customer_id,
                'task_id' => $task->id,
                'type' => Notice::TYPE_TASK_REFUSE,
                'title' => Notice::TYPE_MAP[Notice::TYPE_TASK_REFUSE]['title'],
                'text' => str_replace(['#TASK#', '#LINK#'], [$task->name, Html::a('ссылке', ['tasks/view', 'id' => $task->id])], Notice::TYPE_MAP[Notice::TYPE_TASK_REFUSE]['text']),
            ]);
            if($model->save()) {
                self::send($user->email, $model->title, $model->text);
            }
        }
    }

    /**
     * @param $taskId
     */
    public static function addTaskStart($taskId)
    {
        $task = Tasks::find()
            ->where(['id' => $taskId])
            ->select(['executor_id', 'id'])
            ->one();

        $user = User::findOne(['id' => $task->executor_id]);

        if(!$user) {
            return;
        }

        if($user->setting_action_task) {
            $model = new Notice();
            $model->setAttributes([
                'user_id' => $task->executor_id,
                'task_id' => $task->id,
                'type' => Notice::TYPE_TASK_START,
                'title' => Notice::TYPE_MAP[Notice::TYPE_TASK_START]['title'],
                'text' => str_replace(['#TASK#', '#LINK#'], [$task->name, Html::a('ссылке', ['tasks/view', 'id' => $task->id])], Notice::TYPE_MAP[Notice::TYPE_TASK_START]['text']),
            ]);
            if($model->save()) {
                self::send($user->email, $model->title, $model->text);
            }
        }
    }

    /**
     * @param $taskId
     */
    public static function addTaskEnd($taskId)
    {
        $task = Tasks::find()
            ->where(['id' => $taskId])
            ->select(['executor_id', 'id'])
            ->one();

        $user = User::findOne(['id' => $task->executor_id]);

        if(!$user) {
            return;
        }

        if($user->setting_action_task) {
            $model = new Notice();
            $model->setAttributes([
                'user_id' => $task->executor_id,
                'task_id' => $task->id,
                'type' => Notice::TYPE_TASK_END,
                'title' => Notice::TYPE_MAP[Notice::TYPE_TASK_END]['title'],
                'text' => str_replace(['#TASK#', '#LINK#'], [$task->name, Html::a('ссылке', ['tasks/view', 'id' => $task->id])], Notice::TYPE_MAP[Notice::TYPE_TASK_END]['text']),
            ]);
            if($model->save()) {
                self::send($user->email, $model->title, $model->text);
            }
        }
    }

    /**
     * @param $taskId
     * @param $userId
     */
    public static function addNewMessage($taskId, $userId)
    {
        $task = Tasks::find()
            ->where(['id' => $taskId])
            ->select(['executor_id', 'customer_id', 'id'])
            ->one();

        if($task->executor_id == $userId) {
            $userMessageId = $task->customer_id;
        }
        else {
            $userMessageId = $task->executor_id;
        }

        $user = User::findOne(['id' => $userMessageId]);

        if(!$user) {
            return;
        }

        if($user->setting_new_message) {
            $model = new Notice();
            $model->setAttributes([
                'user_id' => $userMessageId,
                'task_id' => $task->id,
                'type' => Notice::TYPE_NEW_MESSAGE,
                'title' => Notice::TYPE_MAP[Notice::TYPE_NEW_MESSAGE]['title'],
                'text' => str_replace(['#TASK#', '#LINK#'], [$task->name, Html::a('ссылке', ['tasks/view', 'id' => $task->id])], Notice::TYPE_MAP[Notice::TYPE_NEW_MESSAGE]['text']),
            ]);
            if($model->save()) {
                self::send($user->email, $model->title, $model->text);
            }
        }
    }

    /**
     * @param $email
     * @param $title
     * @param $text
     */
    private static function send($email, $title, $text)
    {
        Yii::$app->mailer->compose()
            ->setFrom(Yii::$app->params['senderEmail'])
            ->setTo($email)
            ->setSubject($title)
            ->setTextBody($text)
            ->send();
    }
}
