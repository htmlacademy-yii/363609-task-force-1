<?php
use yii\helpers\Url;

/* @var $this \yii\web\View */
/* @var $model \common\models\User */
/* @var $age string */

$this->title = $model->name;
?>
<section class="content-view">
    <div class="user__card-wrapper">
        <div class="user__card">
            <img src="<?=$model->photo?>" width="120" height="120" alt="<?=$model->name?>">
            <div class="content-view__headline">
                <h1><?=$model->name?></h1>
                <p><?=$model->city->city?>, <?=$age ? $age . ' лет' : ''?></p>
                <div class="profile-mini__name five-stars__rate">
                    <span></span><span></span><span></span><span></span><span class="star-disabled"></span>
                    <b><?=round($model->opinionsRating, 2)?></b>
                </div>
                <b class="done-task">Выполнил <?=count($model->completedTask)?> заказов</b><b class="done-review">Получил <?=count($model->opinions)?> отзывов</b>
            </div>
            <div class="content-view__headline user__card-bookmark user__card-bookmark--current">
                <span>Был на сайте <?=$model->last_activity ? date('d.m.Y H:i:s', strtotime($model->last_activity)) : 'давно'?></span>
                <a href="#" id="favorite"><b data-user-id="<?=$model->id?>"></b></a>
            </div>
        </div>
        <div class="content-view__description">
            <p><?=$model->about?></p>
        </div>
        <div class="user__card-general-information">
            <div class="user__card-info">
                <h3 class="content-view__h3">Специализации</h3>
                <div class="link-specialization">
                    <?php foreach ($model->categories as $category) :?>
                        <a href="#" class="link-regular"><?=$category->category->name?></a>
                    <?php endforeach; ?>
                </div>
                <h3 class="content-view__h3">Контакты</h3>
                <div class="user__card-link">
                    <a class="user__card-link--tel link-regular" href="#"><?=$model->phone?></a>
                    <a class="user__card-link--email link-regular" href="#"><?=$model->email?></a>
                    <a class="user__card-link--skype link-regular" href="#"><?=$model->skype?></a>
                </div>
            </div>
            <div class="user__card-photo">
                <h3 class="content-view__h3">Фото работ</h3>
                <?php foreach ($model->files as $file) :?>
                    <a href="<?=$file->path?>" download><img src="<?=$file->path?>" width="85" height="86" alt="Фото работы"></a>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
    <?php if(!empty($model->opinions)) :?>
        <div class="content-view__feedback">
            <h2>Отзывы<span>(<?=count($model->opinions)?>)</span></h2>
            <div class="content-view__feedback-wrapper reviews-wrapper">
                <?php foreach ($model->opinions as $review) :?>
                    <div class="feedback-card__reviews">
                        <p class="link-task link">Задание <a href="<?=Url::to(['tasks/view', 'id' => $review->task->id])?>" class="link-regular">«<?=$review->task->name?>»</a></p>
                        <div class="card__review">
                            <a href="<?=Url::to(['users/view', 'id' => $review->task->user->id])?>"><img src="<?=$review->task->user->photo?>" width="55" height="54"></a>
                            <div class="feedback-card__reviews-content">
                                <p class="link-name link"><a href="<?=Url::to(['users/view', 'id' => $review->task->user->id])?>" class="link-regular"><?=$review->task->user->name?></a></p>
                                <p class="review-text">
                                    <?=$review->description?>
                                </p>
                            </div>
                            <div class="card__review-rate">
                                <p class="five-rate big-rate"><?=$review->rate?><span></span></p>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    <?php endif; ?>
</section>
<section class="connect-desk">
    <div class="connect-desk__chat">

    </div>
</section>
