<?php
namespace frontend\models\db;

use frontend\models\form\TasksForm;
use Yii;
use frontend\models\db\Tasks;
use yii\data\ActiveDataProvider;

class TasksSearch extends Tasks
{
    public $additional;

    public function search($params)
    {
        $query = Tasks::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => ['dt_add' => SORT_DESC],
            ],
            'pagination' => [
                'pageSize' => 5
            ]
        ]);

        if(!empty($params['period'])) {
            $dateEnd = (new \DateTime())->format('Y-m-d');
            $dateStart = '';
            if($params['period'] == TasksForm::PERIOD_DAY) {
                $dateStart = (new \DateTime('-1 Day'))->format('Y-m-d');
            }
            elseif ($params['period'] == TasksForm::PERIOD_WEEK) {
                $dateStart = (new \DateTime('-7 Day'))->format('Y-m-d');
            }
            elseif ($params['period'] == TasksForm::PERIOD_MONTH) {
                $dateStart = (new \DateTime('-30 Day'))->format('Y-m-d');
            }
        }

        $this->load($params);

        if(!$this->validate()) {
            return $dataProvider;
        }

        $query->where(['tasks.status' => Tasks::STATUS_NEW]);

        $query->andFilterWhere(['tasks.category_id' => $params['categories']??''])
            ->andFilterWhere([
            'like', 'tasks.name', $params['name']??'',
        ]);

        if(!empty($params['additionals'])) {
            $query->joinWith('replies');
            if(in_array(TasksForm::NOT_RESPONSE, $params['additionals']) && in_array(TasksForm::AR_ADDITIONALS, $params['additionals'])) {
                $query->andWhere(['or', ['replies.id' => null], ['city_id' => null]]);
            }
            elseif(in_array(TasksForm::NOT_RESPONSE, $params['additionals'])) {
                $query->andWhere(['replies.id' => null]);
            }
            elseif(in_array(TasksForm::AR_ADDITIONALS, $params['additionals'])) {
                $query->andWhere(['city_id' => null]);
            }
        }

        if(!empty($params['period'])) {
            $query->andFilterWhere(['between', 'tasks.dt_add', $dateStart, $dateEnd]);
        }

        return $dataProvider;

    }

}
