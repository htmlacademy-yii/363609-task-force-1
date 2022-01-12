<?php
/* @var $this yii\web\View
 * @var $modelUser \common\models\User
 * @var $dataProvider \yii\data\ActiveDataProvider
 */
use yii\widgets\ActiveForm;
use frontend\models\form\UsersForm;
use yii\helpers\Html;
use yii\widgets\ListView;

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
    <?php
    echo ListView::widget([
        'dataProvider' => $dataProvider,
        'itemView' => 'user',
        'layout' => '{items}<div class="new-task__pagination">{pager}</div>',
        'pager' => [
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
            'prevPageLabel' => '',
        ],
    ]);
    ?>
</section>
<section  class="search-task">
    <div class="search-task__wrapper">
        <?php $form = ActiveForm::begin([
            'method' => 'get',
            'options' => ['class' => 'search-task__form'],
            'action' => ['users/index']
        ]) ?>
            <fieldset class="search-task__categories">
                <legend>Категории</legend>
                <?=Html::activeCheckboxList($model, 'categories', $model->getCategoriesList(), ['tag' => false, 'value' => $get['categories']??'',
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
                <?=Html::activeCheckbox($model, 'free', ['class' => 'visually-hidden checkbox__input', 'label' => false])?>
                <?=Html::activeLabel($model, 'free')?>
                <?=Html::activeCheckbox($model, 'online', ['class' => 'visually-hidden checkbox__input', 'label' => false])?>
                <?=Html::activeLabel($model, 'online')?>
                <?=Html::activeCheckbox($model, 'haveReviews', ['class' => 'visually-hidden checkbox__input', 'label' => false])?>
                <?=Html::activeLabel($model, 'haveReviews')?>
                <?=Html::activeCheckbox($model, 'favorites', ['class' => 'visually-hidden checkbox__input', 'label' => false])?>
                <?=Html::activeLabel($model, 'favorites')?>
            </fieldset>
        <?=Html::activeLabel($model, 'name', ['class' => 'search-task__name'])?>
        <?=Html::activeInput('search', $model, 'name', ['class' => 'input-middle input'])?>
        <?=Html::button('Искать', ['class' => 'button', 'type' => 'submit'])?>
        <?php ActiveForm::end() ?>
    </div>
</section>
