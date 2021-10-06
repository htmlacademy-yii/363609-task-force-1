<?php
namespace frontend\controllers;

use frontend\models\db\Opinions;
use frontend\models\db\Replies;
use frontend\models\db\Tasks;
use Yii;
use frontend\models\form\TasksForm;
use yii\filters\AccessControl;
use yii\web\NotFoundHttpException;
use frontend\controllers\SecuredController;

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
                'get' => $get['TasksForm']??'',
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
        if(empty($model)) {
            throw new NotFoundHttpException('Задание не найдено');
        }

        $now = new \DateTime(); // текущее время на сервере
        $date = \DateTime::createFromFormat("Y-m-d", $model->customer->dt_add??date('Y-m-d')); // задаем дату в любом формате
        $interval = $now->diff($date); // получаем разницу в виде объекта DateInterval

        if($model->customer_id == Yii::$app->user->identity->id) {
            $replies = $model->replies;
        }
        else {
            $replies = Replies::find()
                ->where(['user_id' => Yii::$app->user->identity->id, 'task_id' => $model->id])
                ->all();
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

        if($model->load($post) && $model->validate()) {
            $model->customer_id = Yii::$app->user->identity->id;
            $coordinate = explode(' ', trim($model->coordinate));
            $model->lat = $coordinate[1] ?? null;
            $model->long = $coordinate[0] ?? null;
            $model->address = explode(';', $model->address)[0] ?? null;
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
            $model = Replies::findOne(['id' => $get['id']]);

            if(isset($model)) {
                if($get['action'] == 'apply') {
                    $model->updateAttributes(['status' => Replies::STATUS_ACCEPT]);
                    $task = Tasks::findOne(['id' => $get['task']]);
                    $task->updateAttributes(['status' => Tasks::STATUS_IN_WORK, 'executor_id' => $model->user_id]);
                    $this->redirect(['tasks/view', 'id' => $task->id]);
                }

                if($get['action'] == 'reject') {
                    $model->updateAttributes(['status' => Replies::STATUS_REJECT]);
                    return ' ';
                }
            }
        }
    }

    /**
     * Сохраняет отклик на задание
     *
     * @return \yii\web\Response
     */
    public function actionRespond()
    {
        $post = Yii::$app->request->post();
        if(!empty($post)) {
            $model = new Replies();
            if($model->load($post) && $model->validate()) {
                $model->user_id = Yii::$app->user->identity->id;
                $model->save();
            }
        }

        return $this->redirect(['tasks/view', 'id' => $post['Replies']['task_id']]);
    }

    /**
     * Отказ от задания исполнителем
     *
     * @return \yii\web\Response
     */
    public function actionRefuse()
    {
        $post = Yii::$app->request->post();
        if(!empty($post) && !empty($post['task_id'])) {
            $model = Tasks::findOne(['id' => $post['task_id']]);
            if(!empty($model) && $model->executor_id == Yii::$app->user->identity->id) {
                $model->updateAttributes(['status' => Tasks::STATUS_FAILED]);
            }
        }

        return $this->redirect(['tasks/view', 'id' => $post['task_id']]);
    }

    /**
     * Отмена задания
     *
     * @return \yii\web\Response
     */
    public function actionCancel()
    {
        $post = Yii::$app->request->post();
        if(!empty($post) && !empty($post['task_id'])) {
            $model = Tasks::findOne(['id' => $post['task_id']]);
            if(!empty($model) && $model->customer_id == Yii::$app->user->identity->id) {
                $model->updateAttributes(['status' => Tasks::STATUS_CANCELED]);
            }
        }

        return $this->redirect(['tasks/view', 'id' => $post['task_id']]);
    }

    /**
     * Завершение задания и отзыв на исполнителя
     *
     * @return \yii\web\Response
     */
    public function actionDone()
    {
        $post = Yii::$app->request->post();
        if(!empty($post)) {

            $modelTask = Tasks::findOne(['id' => $post['Opinions']['task_id']]);
            if($post['completion'] == 'y') {
                $modelTask->updateAttributes(['status' => Tasks::STATUS_COMPLETED]);
            }
            elseif ($post['completion'] == 'n') {
                $modelTask->updateAttributes(['status' => Tasks::STATUS_FAILED]);
            }

            $model = new Opinions();
            if($model->load($post) && $model->validate()) {
                $model->user_id = Yii::$app->user->identity->id;
                $model->save();

            }
        }

        return $this->redirect(['tasks/view', 'id' => $post['Opinions']['task_id']]);
    }

}
