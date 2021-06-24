<?php
namespace console\controllers;

use Yii;
use yii\console\Controller;

class TaskForceRbacController extends Controller
{
    public function actionInit() {
        $auth = Yii::$app->authManager;

        $auth->removeAll();

        //создадим роли
        $admin = $auth->createRole('admin');
        $executor = $auth->createRole('executor');
        $organizer = $auth->createRole('organizer');

        //запишем роли в бд
        $auth->add($admin);
        $auth->add($executor);
        $auth->add($organizer);

        //Создаем разрешения
        $controlSite = $auth->createPermission('controlSite');
        $controlSite->description = 'Управленеи сайтом';

        $completingAssignments = $auth->createPermission('completingTasks');
        $completingAssignments->description = 'Выполнение заданий';

        $creationTasks = $auth->createPermission('creationTasks');
        $creationTasks->description = 'Создание заданий';

        //Запишем эти разрешения в БД
        $auth->add($controlSite);
        $auth->add($completingAssignments);
        $auth->add($creationTasks);

        //наследования

        //исполнителю - исполнять
        $auth->addChild($executor,$completingAssignments);

        //организатору - организовывать
        $auth->addChild($organizer, $creationTasks);

        //админу - всё
        $auth->addChild($admin, $completingAssignments);
        $auth->addChild($admin, $creationTasks);
    }
}
