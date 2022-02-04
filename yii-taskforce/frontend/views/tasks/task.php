<?php
/** @var \frontend\models\db\Tasks $model */
use yii\helpers\Url;
use yii\helpers\Html;
?>
<div class="new-task__card">
    <div class="new-task__title">
        <a href="<?=Url::to(['tasks/view', 'id' => $model->id])?>" class="link-regular"><h2><?=Html::encode($model->name)?></h2></a>
        <a  class="new-task__type link-regular" href="#"><p><?=$model->categories->name?></p></a>
    </div>
    <div class="new-task__icon new-task__icon--<?=$model->categories->icon?>"></div>
    <p class="new-task_description">
        <?=Html::encode($model->description)?>
    </p>
    <?php if($model->budget) :?>
        <b class="new-task__price new-task__price--translation"><?=$model->budget?><b> ₽</b></b>
    <?php endif;?>
    <p class="new-task__place"><?=$model->address?></p>
    <span class="new-task__time"><?=$model->dt_add?></span>
</div>
