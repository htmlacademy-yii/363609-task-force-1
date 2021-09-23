<?php
namespace frontend\controllers;

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
                        'actions' => ['index', 'view', 'button'],
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
        $date = \DateTime::createFromFormat("Y-m-d", $model->customer->dt_add); // задаем дату в любом формате
        $interval = $now->diff($date); // получаем разницу в виде объекта DateInterval

        if($model->customer_id == Yii::$app->user->identity->id) {
            $replies = $model->replies;
        }
        else {
            $replies = Replies::find()
                ->where(['user_id' => Yii::$app->user->identity->id, 'task_id' => $model->id])
                ->all();
        }

        return $this->render('view',
            [
                'model' => $model,
                'interval' => $interval,
                'replies' => $replies
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
            if ($model->save())
                $model->uploadFile();

            return $this->redirect(['tasks/index']);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * кнопки откликов на задание
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
                }

                if($get['action'] == 'reject')
                    $model->updateAttributes(['status' => Replies::STATUS_REJECT]);
            }

            return ' ';
        }
    }

}
