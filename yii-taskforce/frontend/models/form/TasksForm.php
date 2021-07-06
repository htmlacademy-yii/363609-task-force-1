<?php
namespace frontend\models\form;

use yii\base\Model;

class TasksForm extends Model
{
    public const NOT_RESPONSE = 1;
    public const DISTANT_WORK = 2;
    public const AR_ADDITIONALS = [
        self::NOT_RESPONSE => 'Без откликов',
        self::DISTANT_WORK => 'Удалённая работа'
    ];

    public const PERIOD_DAY = 1;
    public const PERIOD_WEEK = 2;
    public const PERIOD_MONTH = 3;
    public const AR_PERIOD = [
        '0' => '',
        self::PERIOD_DAY => 'За день',
        self::PERIOD_WEEK => 'За неделю',
        self::PERIOD_MONTH => 'За месяц'
    ];

    public $categories;
    public $additionals;
    public $period;
    public $name;

    public function attributeLabels() {
        return [
            'categories' => 'Категории',
            'additionals' => 'Дополнительно',
            'period' => 'Период',
            'name' => 'Поиск по названию',
        ];
    }

}
