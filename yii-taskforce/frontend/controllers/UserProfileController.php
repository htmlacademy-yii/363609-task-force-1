<?php

namespace frontend\controllers;

use frontend\models\form\ProfileForm;
use Yii;

class UserProfileController extends SecuredController
{
    public function actionIndex()
    {
        return $this->render('index', [
            'model' => new ProfileForm()
        ]);
    }
}
