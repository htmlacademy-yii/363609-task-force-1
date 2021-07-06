<?php
namespace frontend\models\form;

use yii\base\Model;

class UsersForm extends Model
{
    public const FREE = 1;
    public const ONLINE = 2;
    public const HAVE_REVIEWS = 3;
    public const FAVORITES = 4;
    public const AR_ADDITIONALS = [
        self::FREE => 'Сейчас свободен',
        self::ONLINE => 'Сейчас онлайн',
        self::HAVE_REVIEWS => 'Есть отзывы',
        self::FAVORITES => 'В избранном'
    ];

    public $categories;
    public $additionals;
    public $name;

    public function attributeLabels() {
        return [
            'categories' => 'Категории',
            'additionals' => 'Дополнительно',
            'name' => 'Поиск по названию',
        ];
    }

}
