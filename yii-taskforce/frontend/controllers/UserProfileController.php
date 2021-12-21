<?php

namespace frontend\controllers;

use frontend\models\form\ProfileForm;
use Yii;
use yii\web\UploadedFile;

class UserProfileController extends SecuredController
{
    public function actionIndex()
    {
        $model = new ProfileForm();

        $post = Yii::$app->request->post();

        if(!empty($post)) {
            //если аякс - файлы работ
            if(Yii::$app->request->isAjax) {
                $model->uploadFile();
            }
            else {
                $model->load($post);
                $model->save();
            }
        }

        return $this->render('index', [
            'model' => new ProfileForm()
        ]);
    }
}
