<?php
use frontend\models\db\Tasks;
use yii\helpers\Url;
use yii\helpers\Html;

/* @var $this \yii\web\View */
/* @var $model \frontend\models\db\Tasks */
?>
<div class="new-task__card">
    <div class="new-task__title">
        <a href="<?=Url::to(['tasks/view', 'id' => $model->id])?>" class="link-regular"><h2><?=Html::encode($model->name)?></h2></a>
        <a  class="new-task__type link-regular" href="#"><p><?=$model->categories->name?></p></a>
    </div>
    <div class="task-status done-status"><?=Tasks::STATUSES_LIST[$model->status]?></div>
    <p class="new-task_description">
        <?=Html::encode($model->description)?>
    </p>
    <?php if(!empty($model->executor_id)): ?>
        <div class="feedback-card__top ">
            <a href="<?=Url::to(['users/view', 'id' => $model->executor_id])?>"><img src="<?=$model->executor->photo ?? ''?>" width="36" height="36"></a>
            <div class="feedback-card__top--name my-list__bottom">
                <p class="link-name"><a href="<?=Url::to(['users/view', 'id' => $model->executor_id])?>" class="link-regular"><?=$model->executor->name ?? ''?></a></p>
                <a href="<?=Url::to(['tasks/view', 'id' => $model->id])?>" class="my-list__bottom-chat  my-list__bottom-chat--new"><b><?=count($model->messages)?></b></a>
                <span></span><span></span><span></span><span></span><span class="star-disabled"></span>
                <b><?=$model->executor->getOpinionsRating()?></b>
            </div>
        </div>
    <?php endif; ?>
</div>
