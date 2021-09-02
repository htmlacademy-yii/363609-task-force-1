<?php
namespace frontend\controllers;
use Yii;
use common\models\LoginForm;
use yii\web\Controller;

/**
 * Site controller
 */
class SiteController extends Controller
{
    public $layout = 'landing';

    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return mixed
     */
    public function actionIndex()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->redirect(['tasks/index']);
        }

        $model = new LoginForm();

        return $this->render('index', [
            'model' => $model
        ]);
    }

    public function actionLogin()
    {
        $model = new LoginForm();

        if (Yii::$app->request->isPjax) {
            if ($model->load(Yii::$app->request->post()) && $model->login()) {
                return $this->redirect(['tasks/index']);
            }

            return $this->renderPartial('_loginForm', [
                'model' => $model
            ]);
        }
    }

    /**
     * Logs out the current user.
     *
     * @return mixed
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }
}
