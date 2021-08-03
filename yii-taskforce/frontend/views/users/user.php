<?php
use yii\helpers\Url;
?>
<div class="content-view__feedback-card user__search-wrapper">
    <div class="feedback-card__top">
        <div class="user__search-icon">
            <a href="<?=Url::to(['users/view', 'id' => $model->id])?>"><img src="<?=$model->photo?>" width="65" height="65"></a>
            <span><?=count($model->tasksExecutor)?> заданий</span>
            <span><?=count($model->opinions)?> отзывов</span>
        </div>
        <div class="feedback-card__top--name user__search-card">
            <p class="link-name"><a href="<?=Url::to(['users/view', 'id' => $model->id])?>" class="link-regular"><?=$model->name?></a></p>
            <span></span><span></span><span></span><span></span><span class="star-disabled"></span>
            <b><?=round($model->opinionsRating, 2)?></b>
            <p class="user__search-content">
                <?=$model->profile->about?>
            </p>
        </div>
        <span class="new-task__time">Был на сайте 25 минут назад</span>
    </div>
    <div class="link-specialization user__search-link--bottom">
        <?php foreach ($model->categories as $category) {?>
            <a href="#" class="link-regular"><?=$category->category->name?></a>
        <?php } ?>
    </div>
</div>
