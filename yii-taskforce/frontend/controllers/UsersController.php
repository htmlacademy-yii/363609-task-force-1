<?php
namespace frontend\controllers;
use Yii;
use common\models\User;
use frontend\models\form\UsersForm;
use frontend\models\db\UsersSearch;
use yii\helpers\ArrayHelper;
use yii\data\Pagination;
use frontend\models\db\Categories;

class UsersController extends \yii\web\Controller
{
    public function actionIndex()
    {
        $post = Yii::$app->request->post('UsersForm');
        $modelSearch = new UsersSearch();
        $modelFind = $modelSearch->search($post);
        $users = $modelFind->getModels();
       // $totalCount = $modelFind->getTotalCount();
        $pages = new Pagination(['totalCount' => count($users), 'pageSize' => 5]);
        $pages->pageSizeParam = false;

        $model = new UsersForm();

        $modelUser = User::find()
            ->where(['id' => Yii::$app->authManager->getUserIdsByRole('executor')])
            ->with('profile', 'opinions', 'categories')
            ->orderBy('created_at DESC')
            ->all();

        $arCategories = Categories::find()
            ->select(['id', 'name'])
            ->all();
        $arCategories = ArrayHelper::map($arCategories, 'id', 'name');

        return $this->render('index', compact('users', 'pages', 'arCategories', 'model', 'post'));
    }

}
