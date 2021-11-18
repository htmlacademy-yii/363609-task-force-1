<?php

namespace frontend\controllers;

use DateTime;
use frontend\models\db\Opinions;
use frontend\models\db\Replies;
use frontend\models\db\Tasks;
use frontend\models\form\TasksForm;
use Yii;
use yii\filters\AccessControl;
use yii\web\NotFoundHttpException;
use yii\web\Response;
use yii\web\ServerErrorHttpException;

class TasksController extends SecuredController
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'denyCallback' => function ($rule, $action) {
                    $this->redirect(['site/index']);
                },
                'rules' => [
                    [
                        'actions' => ['create'],
                        'allow' => true,
                        'roles' => ['organizer'],
                    ],
                    [
                        'actions' => ['index', 'view', 'button', 'respond', 'refuse', 'cancel', 'done'],
                        'allow' => true,
                        'roles' => ['@']
                    ],
                ]
            ]
        ];

    }

    /**
     * @return string
     */
    public function actionIndex()
    {
        $get = Yii::$app->request->get();
        $model = new TasksForm();
        $model->load($get);

        return $this->render('index',
            [
                'model' => $model,
                'get' => $get['TasksForm'] ?? '',
                'dataProvider' => $model->getDataProvider()
            ]);
    }

    /**
     * @param $id
     * @return string
     * @throws NotFoundHttpException
     */
    public function actionView($id)
    {
        $model = Tasks::findOne($id);
        if (empty($model)) {
            throw new NotFoundHttpException('Задание не найдено');
        }

        $now = new DateTime(); // текущее время на сервере
        $date = DateTime::createFromFormat("Y-m-d", $model->customer->dt_add ?? date('Y-m-d')); // задаем дату в любом формате
        $interval = $now->diff($date); // получаем разницу в виде объекта DateInterval
        $userId = Yii::$app->user->identity->id;

        if ($model->customer_id == $userId) {
            $replies = $model->replies;
        } else {
            $replies = $model->getReplies()->andWhere(['user_id' => $userId])->all();
        }

        $modelReplies = new Replies();
        $modelOpinions = new Opinions();

        return $this->render('view',
            [
                'model' => $model,
                'interval' => $interval,
                'replies' => $replies,
                'actions' => $model->getAvailableActions(),
                'modelReplies' => $modelReplies,
                'modelOpinions' => $modelOpinions
            ]
        );
    }

    /**
     * @return string
     */
    public function actionCreate()
    {
        $post = Yii::$app->request->post();
        $model = new Tasks();

        if ($model->load($post) && $model->validate()) {
            $model->customer_id = Yii::$app->user->identity->id;
            if ($model->save())
                $model->uploadFile();

            $this->redirect(['tasks/index']);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * кнопки действия с откликами на задание
     *
     * @param $id
     * @return string|void
     */
    public function actionButton($id)
    {
        $get = Yii::$app->request->get();
        if (Yii::$app->request->isPjax && !empty($get['id']) && isset($get['action'])) {
            throw new NotFoundHttpException('Страница не найдена');
        }

        $reply = Replies::findOne(['id' => $get['id']]);
        if (!$reply) {
            throw new NotFoundHttpException('Страница не найдена');
        }

        $task = Tasks::findOne(['id' => $get['task']]);

        if ($get['action'] == 'apply' && $task->getNextStatus(Tasks::ACTION_RESPOND) == Tasks::STATUS_IN_WORK) {
            $reply->updateAttributes(['status' => Replies::STATUS_ACCEPT]);
            $task->updateAttributes(['status' => Tasks::STATUS_IN_WORK, 'executor_id' => $reply->user_id]);
            $this->redirect(['tasks/view', 'id' => $task->id]);
        }

        if ($get['action'] == 'reject') {
            $reply->updateAttributes(['status' => Replies::STATUS_REJECT]);
            return ' ';
        }
    }

    /**
     * Сохраняет отклик на задание
     *
     * @return Response
     */
    public function actionRespond()
    {
        $post = Yii::$app->request->post();

        if (!empty($post)) {
            throw new NotFoundHttpException('Страница не найдена');
        }

        $reply = new Replies();
        if ($reply->load($post) && $reply->validate()) {
            $reply->user_id = Yii::$app->user->identity->id;
            $reply->save();
        }

        return $this->redirect(['tasks/view', 'id' => $post['Replies']['task_id']]);
    }

    /**
     * Отказ от задания исполнителем
     *
     * @return Response
     */
    public function actionRefuse()
    {
        $post = Yii::$app->request->post();
        if (!empty($post) && !empty($post['task_id'])) {
            throw new NotFoundHttpException('Страница не найдена');
        }

        $task = Tasks::findOne(['id' => $post['task_id']]);
        if (!$task) {
            throw new NotFoundHttpException('Страница не найдена');
        }

        $actions = $task->getAvailableActions();

        if (in_array(Tasks::ACTION_REFUSE, $actions) && $task->getNextStatus(Tasks::ACTION_REFUSE) == Tasks::STATUS_FAILED) {
            $task->updateAttributes(['status' => Tasks::STATUS_FAILED]);
        }

        return $this->redirect(['tasks/view', 'id' => $post['task_id']]);
    }

    /**
     * Отмена задания
     *
     * @return Response
     */
    public function actionCancel()
    {
        $post = Yii::$app->request->post();

        if (!empty($post) && !empty($post['task_id'])) {
            throw new NotFoundHttpException('Страница не найдена');
        }

        $task = Tasks::findOne(['id' => $post['task_id']]);
        if (!$task) {
            throw new NotFoundHttpException('Страница не найдена');
        }

        $actions = $task->getAvailableActions();

        if (in_array(Tasks::ACTION_CANCEL, $actions) && $task->getNextStatus(Tasks::ACTION_CANCEL) == Tasks::STATUS_CANCELED) {
            $task->updateAttributes(['status' => Tasks::STATUS_CANCELED]);
        }

        return $this->redirect(['tasks/view', 'id' => $post['task_id']]);
    }

    /**
     * Завершение задания и отзыв на исполнителя
     *
     * @return Response
     */
    public function actionDone()
    {
        $post = Yii::$app->request->post();
        if (!empty($post)) {
            throw new NotFoundHttpException('Страница не найдена');
        }

        $task = Tasks::findOne(['id' => $post['Opinions']['task_id']]);
        if (!$task) {
            throw new NotFoundHttpException('Страница не найдена');
        }

        $actions = $task->getAvailableActions();

        if (in_array(Tasks::ACTION_DONE, $actions) && $task->getNextStatus(Tasks::ACTION_DONE) == Tasks::STATUS_COMPLETED) {

            $transaction = Yii::$app->db->beginTransaction();

            $task->updateAttributes(['status' => $post['completion'] == 'y' ? Tasks::STATUS_COMPLETED : Tasks::STATUS_FAILED]);

            $opinion = new Opinions();
            $opinion->load($post);
            $opinion->user_id = Yii::$app->user->identity->id;
            if (!$opinion->save()) {
                $transaction->rollBack();
                throw new ServerErrorHttpException();
            }

            $transaction->commit();

        }

        return $this->redirect(['tasks/view', 'id' => $post['Opinions']['task_id']]);
    }

}
