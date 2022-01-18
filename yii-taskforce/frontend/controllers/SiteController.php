<?php

namespace frontend\controllers;

use common\models\LoginForm;
use Yii;
use yii\web\Controller;
use frontend\models\db\Auth;
use common\models\User;

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
            'login-vk' => [
                'class' => 'yii\authclient\AuthAction',
                'successCallback' => [$this, 'onAuthSuccess'],
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


    public function onAuthSuccess($client)
    {
        $attributes = $client->getUserAttributes();

        /* @var $auth Auth */
        $auth = Auth::find()->where([
            'source' => $client->getId(),
            'source_id' => $attributes['id'],
        ])->one();

        if (Yii::$app->user->isGuest) {
            if ($auth) { // авторизация
                $user = $auth->user;
                Yii::$app->user->login($user);
            } else { // регистрация
                if (isset($attributes['email']) && User::find()->where(['email' => $attributes['email']])->exists()) {
                    $user = User::find()->where(['email' => $attributes['email']])->one();
                    $auth = new Auth([
                        'user_id' => $user->id,
                        'source' => $client->getId(),
                        'source_id' => (string)$attributes['id'],
                    ]);
                    $auth->save();
                    $user = $auth->user;
                    Yii::$app->user->login($user);
                } else {
                    $password = Yii::$app->security->generateRandomString(6);
                    $user = new User([
                        'name' => $attributes['last_name'] . ' ' .  $attributes['first_name'],
                        'email' => $attributes['email'],
                        'password' => $password,
                        'birthday' => $attributes['bdate']
                    ]);
                    $user->generateAuthKey();
                    $user->generatePasswordResetToken();
                    $transaction = $user->getDb()->beginTransaction();
                    if ($user->save()) {
                        $auth = new Auth([
                            'user_id' => $user->id,
                            'source' => $client->getId(),
                            'source_id' => (string)$attributes['id'],
                        ]);
                        if ($auth->save()) {
                            $transaction->commit();
                            Yii::$app->user->login($user);
                        } else {
                            print_r($auth->getErrors());
                        }
                    } else {
                        print_r($user->getErrors());
                    }
                }
            }
        } else { // Пользователь уже зарегистрирован
            if (!$auth) { // добавляем внешний сервис аутентификации
                $auth = new Auth([
                    'user_id' => Yii::$app->user->id,
                    'source' => $client->getId(),
                    'source_id' => (string)$attributes['id'],
                ]);
                $auth->save();
            }
        }
    }
}
