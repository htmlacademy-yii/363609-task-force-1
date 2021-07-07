<?php
namespace frontend\models\form;

use frontend\models\db\Categories;
use frontend\models\db\Tasks;
use yii\base\Model;
use yii\helpers\ArrayHelper;
use yii\data\ActiveDataProvider;

class TasksForm extends Model
{
    public const NOT_RESPONSE = 1;
    public const DISTANT_WORK = 2;
    public const AR_ADDITIONALS = [
        self::NOT_RESPONSE => 'Без откликов',
        self::DISTANT_WORK => 'Удалённая работа'
    ];

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
    public $additionals;
    public $period;
    public $name;

    public function rules()
    {
        return [
            [['categories'], 'safe'],
            [['additionals', 'period'], 'integer'],
            [['name'], 'string'],
        ];
    }

    public function attributeLabels() {
        return [
            'categories' => 'Категории',
            'additionals' => 'Дополнительно',
            'period' => 'Период',
            'name' => 'Поиск по названию',
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

       // var_dump($this->categories);

        $query->andFilterWhere(['tasks.category_id' => $this->categories]);

        $query->andFilterWhere([
                'like', 'tasks.name', $this->name,
            ]);

        if(!empty($this->additionals)) {
            if(in_array(self::NOT_RESPONSE, $this->additionals) && in_array(self::AR_ADDITIONALS, $this->additionals)) {
                $query->joinWith('replies');
                $query->andWhere(['or', ['replies.id' => null], ['city_id' => null]]);
            }
            elseif(in_array(self::NOT_RESPONSE, $this->additionals)) {
                $query->joinWith('replies');
                $query->andWhere(['replies.id' => null]);
            }
            elseif(in_array(self::DISTANT_WORK, $this->additionals)) {
                $query->andWhere(['city_id' => null]);
            }
        }

        if(!empty($this->period)) {
            $query->andWhere('DATE(dt_add) >= DATE(NOW() - INTERVAL :period DAY)', ['period' => $this->period]);
        }

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
