<?php
namespace frontend\controllers;

use frontend\models\db\Categories;
use frontend\models\db\Tasks;
use Yii;
use frontend\models\db\TasksSearch;
use yii\data\Pagination;
use yii\helpers\ArrayHelper;
use frontend\models\form\TasksForm;

class TasksController extends \yii\web\Controller
{
    public function actionIndex()
    {
        $post = Yii::$app->request->post('TasksForm');
        $modelSearch = new TasksSearch();
        $modelFind = $modelSearch->search($post);
        $tasks = $modelFind->getModels();
        //$totalCount = $modelFind->getTotalCount();
        $pages = new Pagination(['totalCount' => count($tasks), 'pageSize' => 5]);
        $pages->pageSizeParam = false;

        $model = new TasksForm();

        $arCategories = Categories::find()
            ->select(['id', 'name'])
            ->all();
        $arCategories = ArrayHelper::map($arCategories, 'id', 'name');

        return $this->render('index', compact('tasks', 'pages', 'arCategories', 'model', 'post'));
    }

}
