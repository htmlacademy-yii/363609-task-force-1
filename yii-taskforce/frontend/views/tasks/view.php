<?php
use yii\helpers\Url;

/* @var $this \yii\web\View */
/* @var $model */

$this->title = $model->name;
?>

<section class="content-view">
    <div class="content-view__card">
        <div class="content-view__card-wrapper">
            <div class="content-view__header">
                <div class="content-view__headline">
                    <h1><?=$model->name?></h1>
                    <span>Размещено в категории
                                    <a href="#" class="link-regular"><?=$model->categories->name?></a>
                                    <?=Yii::$app->formatter->asDate($model->dt_add, 'php:d.m.Y')?></span>
                </div>
                <b class="new-task__price new-task__price--clean content-view-price"><?=$model->budget?><b> ₽</b></b>
                <div class="new-task__icon new-task__icon--<?=$model->categories->icon?> content-view-icon"></div>
            </div>
            <div class="content-view__description">
                <h3 class="content-view__h3">Общее описание</h3>
                <p>
                    <?=$model->description?>
                </p>
            </div>
            <div class="content-view__attach">
                <h3 class="content-view__h3">Вложения</h3>
                <?php foreach ($model->files as $file) :?>
                    <a href="<?=$file->path?>" download><?=$file->name?></a>
                <?php endforeach; ?>
            </div>
            <div class="content-view__location">
                <h3 class="content-view__h3">Расположение</h3>
                <div class="content-view__location-wrapper">
                    <div class="content-view__map">
                        <a href="#"><img src="./img/map.jpg" width="361" height="292"
                                         alt="Москва, Новый арбат, 23 к. 1"></a>
                    </div>
                    <div class="content-view__address">
                        <span class="address__town">Москва</span><br>
                        <span>Новый арбат, 23 к. 1</span>
                        <p>Вход под арку, код домофона 1122</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="content-view__action-buttons">
            <button class=" button button__big-color response-button open-modal"
                    type="button" data-for="response-form">Откликнуться</button>
            <button class="button button__big-color refusal-button open-modal"
                    type="button" data-for="refuse-form">Отказаться</button>
            <button class="button button__big-color request-button open-modal"
                    type="button" data-for="complete-form">Завершить</button>
        </div>
    </div>
    <div class="content-view__feedback">
        <h2>Отклики <span>(<?=count($replies)?>)</span></h2>
        <div class="content-view__feedback-wrapper">
            <?php foreach ($replies as $reply) :?>
                <div class="content-view__feedback-card">
                    <div class="feedback-card__top">
                        <a href="<?=Url::to(['users/view', 'id' => $reply->user->id])?>"><img src="<?=$reply->user->photo?>" width="55" height="55"></a>
                        <div class="feedback-card__top--name">
                            <p><a href="<?=Url::to(['users/view', 'id' => $reply->user->id])?>" class="link-regular"><?=$reply->user->name?></a></p>
                            <span></span><span></span><span></span><span></span><span class="star-disabled"></span>
                            <b><?=round($reply->user->opinionsRating, 2)?></b>
                        </div>
                        <span class="new-task__time">25 минут назад</span>
                    </div>
                    <div class="feedback-card__content">
                        <p>
                            <?=$reply->description?>
                        </p>
                        <span><?=$model->budget?> ₽</span>
                    </div>
                    <div class="feedback-card__actions">
                        <a class="button__small-color request-button button"
                           type="button">Подтвердить</a>
                        <a class="button__small-color refusal-button button"
                           type="button">Отказать</a>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<section class="connect-desk">
    <div class="connect-desk__profile-mini">
        <div class="profile-mini__wrapper">
            <h3>Заказчик</h3>
            <div class="profile-mini__top">
                <img src="<?=$model->customer->photo?>" width="62" height="62" alt="Аватар заказчика">
                <div class="profile-mini__name five-stars__rate">
                    <p><?=$model->customer->name?></p>
                </div>
            </div>
            <p class="info-customer"><span><?=count($model->customer->tasksCustomer)?> заданий</span><span class="last-"><?=$interval->y?> год на сайте</span></p>
            <a href="<?=Url::to(['users/view', 'id' => $model->customer->id])?>" class="link-regular">Смотреть профиль</a>
        </div>
    </div>
    <div id="chat-container">
        <!--                    добавьте сюда атрибут task с указанием в нем id текущего задания-->
        <chat class="connect-desk__chat"></chat>
    </div>
</section>
