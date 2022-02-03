<?php
use yii\helpers\Url;
use yii\helpers\StringHelper;
use yii\helpers\Html;
/* @var $this yii\web\View */
/* @var $model \common\models\LoginForm */
/* @var $task array|\frontend\models\db\Tasks[] */

$this->title = 'Главная';

$this->params['modelLogin'] = $model;
?>

<div class="landing-top">
    <h1>Работа для всех.<br>
        Найди исполнителя на любую задачу.</h1>
    <p>Сломался кран на кухне? Надо отправить документы? Нет времени самому гулять с собакой?
        У нас вы быстро найдёте исполнителя для любой жизненной ситуации?<br>
        Быстро, безопасно и с гарантией. Просто, как раз, два, три. </p>
    <a href="<?=Url::to(['registration/index'])?>" class="button">
        Создать аккаунт
    </a>
</div>
<div class="landing-center">
    <div class="landing-instruction">
        <div class="landing-instruction-step">
            <div class="instruction-circle circle-request"></div>
            <div class="instruction-description">
                <h3>Публикация заявки</h3>
                <p>Создайте новую заявку.</p>
                <p>Опишите в ней все детали
                    и  стоимость работы.</p>
            </div>
        </div>
        <div class="landing-instruction-step">
            <div class="instruction-circle  circle-choice"></div>
            <div class="instruction-description">
                <h3>Выбор исполнителя</h3>
                <p>Получайте отклики от мастеров.</p>
                <p>Выберите подходящего<br>
                    вам исполнителя.</p>
            </div>
        </div>
        <div class="landing-instruction-step">
            <div class="instruction-circle  circle-discussion"></div>
            <div class="instruction-description">
                <h3>Обсуждение деталей</h3>
                <p>Обсудите все детали работы<br>
                    в нашем внутреннем чате.</p>
            </div>
        </div>
        <div class="landing-instruction-step">
            <div class="instruction-circle circle-payment"></div>
            <div class="instruction-description">
                <h3>Оплата&nbsp;работы</h3>
                <p>По завершении работы оплатите
                    услугу и закройте задание</p>
            </div>
        </div>
    </div>
    <div class="landing-notice">
        <div class="landing-notice-card card-executor">
            <h3>Исполнителям</h3>
            <ul class="notice-card-list">
                <li>
                    Большой выбор заданий
                </li>
                <li>
                    Работайте где  удобно
                </li>
                <li>
                    Свободный график
                </li>
                <li>
                    Удалённая работа
                </li>
                <li>
                    Гарантия оплаты
                </li>
            </ul>
        </div>
        <div class="landing-notice-card card-customer">
            <h3>Заказчикам</h3>
            <ul class="notice-card-list">
                <li>
                    Исполнители на любую задачу
                </li>
                <li>
                    Достоверные отзывы
                </li>
                <li>
                    Оплата по факту работы
                </li>
                <li>
                    Экономия времени и денег
                </li>
                <li>
                    Выгодные цены
                </li>
            </ul>
        </div>
    </div>
</div>
<div class="landing-bottom">
    <div class="landing-bottom-container">
        <h2>Последние задания на сайте</h2>
        <?php foreach ($task as $item):?>
            <div class="landing-task">
                <div class="landing-task-top task-<?=$item->categories->icon?>"></div>
                <div class="landing-task-description">
                    <h3><a href="<?=Url::to(['tasks/view', 'id' => $item->id])?>" class="link-regular"><?=StringHelper::truncate(Html::encode($item->name),20,'...')?></a></h3>
                    <p><?=StringHelper::truncate(Html::encode($item->description),20,'...')?></p>
                </div>
                <div class="landing-task-info">
                    <div class="task-info-left">
                        <p><a href="#" class="link-regular"><?=$item->categories->name?></a></p>
                        <p><?=$item->dt_add?></p>
                    </div>
                    <span><?=$item->budget?> <b>₽</b></span>
                </div>
            </div>
        <?php endforeach;?>
    </div>
    <div class="landing-bottom-container">
        <a href="<?=Url::to(['tasks/index'])?>" class="button red-button">
            смотреть все задания
        </a>
    </div>
</div>
