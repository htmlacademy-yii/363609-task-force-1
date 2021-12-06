<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;

/**
 * @var $model \frontend\models\db\Tasks
 */
$this->title = 'Создание задания';
$this->params['life_address'] = true;
?>
<section class="create__task">
    <h1><?=$this->title?></h1>
    <div class="create__task-main">
        <?php ActiveForm::begin([
            'action' => ['tasks/create'],
            'method' => 'post',
            'options' => [
                'class' => 'create__task-form form-create',
                'enctype' => 'multipart/form-data',
                'id' => 'task-form'
            ]
        ])?>
            <?=Html::activeLabel($model, 'name')?>
            <?=Html::activeTextarea($model, 'name', ['class' => 'input textarea', 'rows' => 1])?>
            <span>Кратко опишите суть работы</span>
            <?php if($model->hasErrors('name')):?>
                <span style="color: red"><?=$model->getFirstError('name')?></span>
            <?php endif; ?>
            <?=Html::activeLabel($model, 'description')?>
            <?=Html::activeTextarea($model, 'description', ['class' => 'input textarea', 'rows' => 7])?>
            <span>Укажите все пожелания и детали, чтобы исполнителям было проще соориентироваться</span>
            <?php if($model->hasErrors('description')):?>
                <span style="color: red"><?=$model->getFirstError('description')?></span>
            <?php endif; ?>
            <?=Html::activeLabel($model, 'category_id')?>
            <?=Html::activeDropDownList($model, 'category_id', ArrayHelper::map($model->getAllCategories(),'id', 'name'),
                ['class' => 'multiple-select input multiple-select-big', 'size' => 1, 'prompt' => 'Выберите категорию'])?>
            <span>Выберите категорию</span>
            <?php if($model->hasErrors('category_id')):?>
                <span style="color: red"><?=$model->getFirstError('category_id')?></span>
            <?php endif; ?>
            <?=Html::activeLabel($model, 'files')?>
            <span>Загрузите файлы, которые помогут исполнителю лучше выполнить или оценить работу</span>
            <div class="create__file">
                <span>Добавить новый файл</span>
                <?=Html::activeFileInput($model, 'files[]', ['class' => 'dropzone'])?>
            </div>
            <?php if($model->hasErrors('files')):?>
                <span style="color: red"><?=$model->getFirstError('files')?></span>
            <?php endif; ?>
            <?=Html::activeLabel($model, 'address')?>
            <?=Html::activeTextInput($model, 'address', ['class' => 'input-navigation input-middle input', 'type' => 'search', 'id' => 'autoComplete'])?>
            <?=Html::activeHiddenInput($model, 'coordinate', ['id' => 'coordinate'])?>
            <span>Укажите адрес исполнения, если задание требует присутствия</span>
            <?php if($model->hasErrors('address')):?>
                <span style="color: red"><?=$model->getFirstError('address')?></span>
            <?php endif; ?>
            <div class="create__price-time">
                <div class="create__price-time--wrapper">
                    <?=Html::activeLabel($model, 'budget')?>
                    <?=Html::activeTextarea($model, 'budget', ['class' => 'input textarea input-money', 'rows' => 1])?>
                    <span>Не заполняйте для оценки исполнителем</span>
                    <?php if($model->hasErrors('budget')):?>
                        <span style="color: red"><?=$model->getFirstError('budget')?></span>
                    <?php endif; ?>
                </div>
                <div class="create__price-time--wrapper">
                    <?=Html::activeLabel($model, 'expire')?>
                    <?=Html::activeTextInput($model, 'expire', ['class' => 'input-middle input input-date', 'type' => 'date'])?>
                    <span>Укажите крайний срок исполнения</span>
                    <?php if($model->hasErrors('expire')):?>
                        <span style="color: red"><?=$model->getFirstError('expire')?></span>
                    <?php endif; ?>
                </div>
            </div>
        <?php ActiveForm::end() ?>
        <div class="create__warnings">
            <div class="warning-item warning-item--advice">
                <h2>Правила хорошего описания</h2>
                <h3>Подробности</h3>
                <p>Друзья, не используйте случайный<br>
                    контент – ни наш, ни чей-либо еще. Заполняйте свои
                    макеты, вайрфреймы, мокапы и прототипы реальным
                    содержимым.</p>
                <h3>Файлы</h3>
                <p>Если загружаете фотографии объекта, то убедитесь,
                    что всё в фокусе, а фото показывает объект со всех
                    ракурсов.</p>
            </div>
            <div class="warning-item warning-item--error">
                <h2>Ошибки заполнения формы</h2>
                <h3>Категория</h3>
                <p>Это поле должно быть выбрано.<br>
                    Задание должно принадлежать одной из категорий</p>
            </div>
        </div>
    </div>
    <button form="task-form" class="button" type="submit">Опубликовать</button>
</section>
