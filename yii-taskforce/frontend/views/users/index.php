<?php
/* @var $this yii\web\View
 * @var $modelUser \common\models\User
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
                <a href="" class="link-regular">Числу заказов</a>
            </li>
            <li class="user__search-item">
                <a href="" class="link-regular">Популярности</a>
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
            'options' => ['class' => 'search-task__form'],
        ]) ?>
            <fieldset class="search-task__categories">
                <legend>Категории</legend>
                <?=Html::activeCheckboxList($model, 'categories', $model->getCategoriesList(), ['tag' => false, 'value' => $post['categories']??'',
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
