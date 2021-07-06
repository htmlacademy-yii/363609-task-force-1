<?php
/* @var $this yii\web\View
 * @var $modelUser \common\models\User
 */
use yii\widgets\ActiveForm;
use frontend\models\form\UsersForm;
use yii\widgets\LinkPager;
use yii\helpers\Html;

$this->title = 'Исполнители';
?>
<section class="user__search">
    <div class="user__search-link">
        <p>Сортировать по:</p>
        <ul class="user__search-list">
            <li class="user__search-item user__search-item--current">
                <a href="?sort=rating" class="link-regular">Рейтингу</a>
            </li>
            <li class="user__search-item">
                <a href="?sort=tasks" class="link-regular">Числу заказов</a>
            </li>
            <li class="user__search-item">
                <a href="?sort=review" class="link-regular">Популярности</a>
            </li>
        </ul>
    </div>
    <?php foreach ($users as $item) {?>
        <div class="content-view__feedback-card user__search-wrapper">
                <div class="feedback-card__top">
                    <div class="user__search-icon">
                        <a href="#"><img src="<?=$item->photo?>" width="65" height="65"></a>
                        <span><?=count($item->tasksExecutor)?> заданий</span>
                        <span><?=count($item->opinions)?> отзывов</span>
                    </div>
                    <div class="feedback-card__top--name user__search-card">
                        <p class="link-name"><a href="#" class="link-regular"><?=$item->name?></a></p>
                        <span></span><span></span><span></span><span></span><span class="star-disabled"></span>
                        <b><?=round($item->opinionsRating, 2)?></b>
                        <p class="user__search-content">
                            <?=$item->profile->about?>
                        </p>
                    </div>
                    <span class="new-task__time">Был на сайте 25 минут назад</span>
                </div>
            <div class="link-specialization user__search-link--bottom">
                <?php foreach ($item->categories as $category) {?>
                    <a href="#" class="link-regular"><?=$category->category->name?></a>
                <?php } ?>
            </div>
        </div>
    <?php } ?>
    <div class="new-task__pagination">
        <?php
        echo LinkPager::widget([
            'pagination' => $pages,
            'options' => [
                'class' => 'new-task__pagination-list'
            ],
            'linkContainerOptions' => [
                'class' => 'pagination__item',
            ],
            'activePageCssClass' => 'pagination__item--current',
            'prevPageCssClass' => '',
            'nextPageCssClass' => '',
            'nextPageLabel' => '',
            'prevPageLabel' => ''
        ]);
        ?>
    </div>
</section>
<section  class="search-task">
    <div class="search-task__wrapper">
        <?php $form = ActiveForm::begin([
            'options' => ['class' => 'search-task__form'],
        ]) ?>
            <fieldset class="search-task__categories">
                <legend>Категории</legend>
                <?=Html::activeCheckboxList($model, 'categories', $arCategories, ['tag' => false, 'value' => $post['categories']??'',
                    'item' => function ($index, $label, $name, $checked, $value) {
                        $checked = $checked ? 'checked' : '';
                        return
                            "
                   <input type='checkbox' class='visually-hidden checkbox__input'  name='{$name}' value='{$value}' {$checked} id='categories-{$index}'>
                   <label for='categories-{$index}'>
                    {$label}
                    </label>
                    ";
                    }])?>
            </fieldset>
            <fieldset class="search-task__categories">
                <legend>Дополнительно</legend>
                <?=Html::activeCheckboxList($model, 'additionals', UsersForm::AR_ADDITIONALS, ['tag' => false, 'value' => $post['additionals']??'',
                    'item' => function ($index, $label, $name, $checked, $value) {
                        $checked = $checked ? 'checked' : '';
                        return
                            "
                   <input type='checkbox' class='visually-hidden checkbox__input'  name='{$name}' value='{$value}' {$checked} id='additionals-{$index}'>
                   <label for='additionals-{$index}'>
                    {$label}
                    </label>
                    ";
                    }])?>
            </fieldset>
        <?=Html::activeLabel($model, 'name', ['class' => 'search-task__name'])?>
        <?=Html::activeInput('search', $model, 'name', ['class' => 'input-middle input'])?>
        <?=Html::button('Искать', ['class' => 'button', 'type' => 'submit'])?>
        <?php ActiveForm::end() ?>
    </div>
</section>
