<?php

namespace src\model;

use common\models\User;
use frontend\models\db\Auth;
use Yii;

class AuthModel
{
    /**
     * @param $userId
     * @param $client
     * @param $attributes
     *
     * @return Auth
     */
    public static function addAuth($userId, $client, $attributes)
    {
        $auth = new Auth([
            'user_id' => $userId,
            'source' => $client->getId(),
            'source_id' => (string)$attributes['id'],
        ]);
        $auth->save();

        return $auth;
    }

    /**
     * @param $client
     * @param $attributes
     *
     * @throws \yii\base\Exception
     * @throws \yii\db\Exception
     */
    public static function addUser($client, $attributes)
    {
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

            $auth = Yii::$app->authManager;
            $user_permission = $auth->getRole('organizer');
            $auth->assign($user_permission, $user->id);

            $auth = self::addAuth($user->id, $client, $attributes);
            if ($auth->save()) {
                $transaction->commit();
                Yii::$app->user->login($user);
            } else {
                $transaction->rollBack();
            }
        }
    }

}
