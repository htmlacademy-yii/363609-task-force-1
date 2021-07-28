<?php
namespace frontend\models\form;

use frontend\models\db\Categories;
use frontend\models\db\Tasks;
use yii\base\Model;
use yii\helpers\ArrayHelper;
use yii\data\ActiveDataProvider;

class TasksForm extends Model
{
    public const PERIOD_DEFAULT = 0;
    public const PERIOD_DAY = 1;
    public const PERIOD_WEEK = 7;
    public const PERIOD_MONTH = 30;
    public const AR_PERIOD = [
        self::PERIOD_DEFAULT => '',
        self::PERIOD_DAY => 'За день',
        self::PERIOD_WEEK => 'За неделю',
        self::PERIOD_MONTH => 'За месяц'
    ];

    public $categories;
    public $period;
    public $name;
    public $notResponse;
    public $distantWork;


    public function rules()
    {
        return [
            [['categories'], 'safe'],
            [['notResponse', 'distantWork', 'period'], 'integer'],
            [['name'], 'string'],
        ];
    }

    public function attributeLabels() {
        return [
            'categories' => 'Категории',
            'period' => 'Период',
            'name' => 'Поиск по названию',
            'notResponse' => 'Без откликов',
            'distantWork' => 'Удалённая работа',
        ];
    }

    public function getCategoriesList()
    {
        $arCategories = Categories::find()
            ->select(['id', 'name'])
            ->all();
        return ArrayHelper::map($arCategories, 'id', 'name');
    }

    public function getDataProvider()
    {
        $query = Tasks::find();

        $query->where(['tasks.status' => Tasks::STATUS_NEW]);

        $query->andFilterWhere(['tasks.category_id' => $this->categories]);

        $query->andFilterWhere([
                'like', 'tasks.name', $this->name,
            ]);

        if(!empty($this->notResponse)) {
            $query->joinWith('replies');
            $query->andWhere(['replies.id' => null]);
        }

        if(!empty($this->distantWork)) {
            $query->andWhere(['city_id' => null]);
        }

        if(!empty($this->period)) {
            $query->andWhere('DATE(dt_add) >= DATE(NOW() - INTERVAL :period DAY)', ['period' => $this->period]);
        }

        $query->groupBy('tasks.id');

        return new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => ['dt_add' => SORT_DESC],
            ],
            'pagination' => [
                'pageSize' => 5,
            ]
        ]);
    }

}
