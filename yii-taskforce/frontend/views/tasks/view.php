<?php
use yii\helpers\Url;
use yii\widgets\Pjax;
use yii\helpers\Html;
use frontend\models\db\Tasks;
use frontend\models\db\Replies;

/* @var $this \yii\web\View */
/* @var Tasks $model */
/* @var array $actions */
/* @var Replies $modelReplies */
/* @var \frontend\models\db\Opinions $modelOpinions */

$this->title = $model->name;
$this->params['modals_actions'] = true;
$this->params['task_id'] = $model->id;
$this->params['model_replies'] = $modelReplies;
$this->params['model_opinions'] = $modelOpinions;
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
            <?php if($model->files) :?>
                <div class="content-view__attach">
                    <h3 class="content-view__h3">Вложения</h3>
                    <?php foreach ($model->files as $file) :?>
                        <a href="<?=$file->path?>" download><?=$file->name?></a>
                    <?php endforeach; ?>
                </div>
            <?php endif;?>
            <div class="content-view__location">
                <h3 class="content-view__h3">Расположение</h3>
                <div class="content-view__location-wrapper">
                    <div class="content-view__map" id="map" style="width: 361px; height: 292px">
                    </div>
                    <div class="content-view__address">
                        <span><?=$model->address?></span>
                        <?php /*
                        <span class="address__town">Москва</span><br>
                        <span>Новый арбат, 23 к. 1</span>
                        <p>Вход под арку, код домофона 1122</p>
                        */?>
                    </div>
                </div>
            </div>
        </div>
        <div class="content-view__action-buttons">
            <?php if(Yii::$app->user->can('executor') && empty($replies) && in_array(Tasks::ACTION_RESPOND, $actions)) :?>
                <button class=" button button__big-color response-button open-modal"
                        type="button" data-for="response-form">Откликнуться</button>
            <?php endif;?>
            <?php if(in_array(Tasks::ACTION_REFUSE, $actions)) :?>
                <button class="button button__big-color refusal-button open-modal"
                        type="button" data-for="refuse-form">Отказаться</button>
            <?php endif;?>
            <?php if(in_array(Tasks::ACTION_CANCEL, $actions)) :?>
                <button class="button button__big-color refusal-button open-modal"
                        type="button" data-for="cancel-form">Отменить</button>
            <?php endif;?>
            <?php if(in_array(Tasks::ACTION_DONE, $actions)) :?>
                <button class="button button__big-color request-button open-modal"
                        type="button" data-for="complete-form">Завершить</button>
            <?php endif;?>
        </div>
    </div>
    <?php if(!empty($replies)):?>
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
                            <span class="new-task__time"><?=Yii::$app->formatter->asDatetime($reply->user->last_activity, 'php:d.m.Y H:i:s')?></span>
                        </div>
                        <div class="feedback-card__content">
                            <p>
                                <?=$reply->description?>
                            </p>
                            <span><?=$reply->price?> ₽</span>
                        </div>
                        <?php if($model->customer_id == Yii::$app->user->identity->id && ($model->status == Tasks::STATUS_NEW && $reply->status == Replies::STATUS_NEW)): ?>
                            <div class="feedback-card__actions">
                                <?= Html::a(
                                    'Подтвердить',
                                    ['tasks/button', 'id' => $reply->id, 'action' => 'apply', 'task' => $model->id],
                                    ['class' => 'button__small-color request-button button', 'type' => 'button']
                                ) ?>
                                <?= Html::a(
                                    'Отказать',
                                    ['tasks/button', 'id' => $reply->id, 'action' => 'reject'],
                                    ['class' => 'button__small-color refusal-button button', 'type' => 'button']
                                ) ?>
                            </div>
                            <?php endif;?>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    <?php endif;?>
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
    <?php if(!empty($model->executor_id) && (Yii::$app->user->identity->id == $model->executor_id || Yii::$app->user->identity->id == $model->customer_id)): ?>
    <div id="chat-container">
        <chat class="connect-desk__chat" task="<?=$model->id?>" user="<?=Yii::$app->user->identity->id?>"></chat>
    </div>
    <?php endif; ?>
</section>
<script type="text/javascript">
    // Функция ymaps.ready() будет вызвана, когда
    // загрузятся все компоненты API, а также когда будет готово DOM-дерево.
    ymaps.ready(init);
    function init(){
        // Создание карты.
            var myMap = new ymaps.Map("map", {
                center: [<?=$model->lat?>, <?=$model->long?>],
                zoom: 7
            }),
            myGeoObject = new ymaps.GeoObject({
                // Описание геометрии.
                geometry: {
                    type: "Point",
                    coordinates: [<?=$model->lat?>, <?=$model->long?>]
                },
                // Свойства.
                properties: {
                    // Контент метки.
                    iconContent: '<?=$model->address?>'
                }
            }, {
                preset: 'islands#blackStretchyIcon',
                draggable: false
            });
            myMap.geoObjects.add(myGeoObject)
    }
</script>
