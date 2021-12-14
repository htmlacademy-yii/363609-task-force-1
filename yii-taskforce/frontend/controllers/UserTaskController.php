<?php

namespace frontend\controllers;

use Yii;
use frontend\models\db\Tasks;
use yii\data\ActiveDataProvider;

class UserTaskController extends SecuredController
{
    public function actionIndex()
    {
        return $this->render('index', [
            'dataProvider' => new ActiveDataProvider([
                'query' => Tasks::find()
                    ->with('executor', 'messages')
                    ->where(
                    [
                        'status' => Tasks::STATUS_NEW,
                        'customer_id' => Yii::$app->user->identity->id
                    ]
                ),
                'sort' => [
                    'defaultOrder' => ['dt_add' => SORT_DESC],
                ],
                'pagination' => [
                    'pageSize' => 10,
                ]
            ])
        ]);
    }

    public function actionCanceled()
    {
        return $this->render('index', [
            'dataProvider' => new ActiveDataProvider([
                'query' => Tasks::find()
                    ->with('executor', 'messages')
                    ->where(
                        [
                            'OR',
                            ['customer_id' => Yii::$app->user->identity->id],
                            ['executor_id' => Yii::$app->user->identity->id],
                        ]
                    )
                    ->andWhere(
                        [
                            'status' => [Tasks::STATUS_FAILED, Tasks::STATUS_CANCELED],
                        ]
                    )
                ,
                'sort' => [
                    'defaultOrder' => ['dt_add' => SORT_DESC],
                ],
                'pagination' => [
                    'pageSize' => 10,
                ]
            ])
        ]);
    }

    public function actionInWork()
    {
        return $this->render('index', [
            'dataProvider' => new ActiveDataProvider([
                'query' => Tasks::find()
                    ->with('executor', 'messages')
                    ->where(
                        [
                            'OR',
                            ['customer_id' => Yii::$app->user->identity->id],
                            ['executor_id' => Yii::$app->user->identity->id],
                        ]
                    )
                    ->andWhere(
                        [
                            'status' => Tasks::STATUS_IN_WORK,
                        ]
                    )
                ,
                'sort' => [
                    'defaultOrder' => ['dt_add' => SORT_DESC],
                ],
                'pagination' => [
                    'pageSize' => 10,
                ]
            ])
        ]);
    }

    public function actionCompleted()
    {
        return $this->render('index', [
            'dataProvider' => new ActiveDataProvider([
                'query' => Tasks::find()
                    ->with('executor', 'messages')
                    ->where(
                        [
                            'OR',
                            ['customer_id' => Yii::$app->user->identity->id],
                            ['executor_id' => Yii::$app->user->identity->id],
                        ]
                    )
                    ->andWhere(
                        [
                            'status' => Tasks::STATUS_COMPLETED,
                        ]
                    )
                ,
                'sort' => [
                    'defaultOrder' => ['dt_add' => SORT_DESC],
                ],
                'pagination' => [
                    'pageSize' => 10,
                ]
            ])
        ]);
    }

    public function actionExpired()
    {
        return $this->render('index', [
            'dataProvider' => new ActiveDataProvider([
                'query' => Tasks::find()
                    ->with('executor', 'messages')
                    ->where(
                        [
                            'OR',
                            ['customer_id' => Yii::$app->user->identity->id],
                            ['executor_id' => Yii::$app->user->identity->id],

                        ]
                    )
                    ->andWhere(
                        [
                            '<', 'expire', date('Y-m-d')
                        ]
                    )
                ,
                'sort' => [
                    'defaultOrder' => ['dt_add' => SORT_DESC],
                ],
                'pagination' => [
                    'pageSize' => 10,
                ]
            ])
        ]);
    }
}
