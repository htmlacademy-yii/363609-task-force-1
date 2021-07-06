<?php
/* @var $this yii\web\View
 * @var $model
 */
use yii\widgets\ActiveForm;
use yii\widgets\LinkPager;
use yii\helpers\Html;
use frontend\models\form\TasksForm;

$this->title = 'Новые задания';
?>
<section class="new-task">
    <div class="new-task__wrapper">
        <h1><?=$this->title?></h1>
        <?php foreach ($tasks as $item) {?>
            <div class="new-task__card">
                <div class="new-task__title">
                    <a href="<?=$item->id?>" class="link-regular"><h2><?=$item->name?></h2></a>
                    <a  class="new-task__type link-regular" href="#"><p><?=$item->categories->name?></p></a>
                </div>
                <div class="new-task__icon new-task__icon--<?=$item->categories->icon?>"></div>
                <p class="new-task_description">
                    <?=$item->description?>
                </p>
                <b class="new-task__price new-task__price--translation"><?=$item->budget?><b> ₽</b></b>
                <p class="new-task__place"><?=$item->address?></p>
                <span class="new-task__time"><?=$item->dt_add?></span>
            </div>
        <?php } ?>
    </div>
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
                <?=Html::activeCheckboxList($model, 'additionals', TasksForm::AR_ADDITIONALS, ['tag' => false, 'value' => $post['additionals']??'',
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
            <?=Html::activeLabel($model, 'period', ['class' => 'search-task__name'])?>
            <?=Html::activeDropDownList($model, 'period', TasksForm::AR_PERIOD,
                ['class' => 'multiple-select input', 'size' => '1','value' => $post['period']??''])?>

            <?=Html::activeLabel($model, 'name', ['class' => 'search-task__name'])?>
            <?=Html::activeInput('search', $model, 'name', ['class' => 'input-middle input'])?>

            <?=Html::button('Искать', ['class' => 'button', 'type' => 'submit'])?>
            <?php ActiveForm::end() ?>
    </div>
</section>
