<?php
/**
 * @var $model \common\models\User
 * @var $this \yii\web\View
 */
use yii\helpers\Html;
use yii\widgets\ActiveForm;

$this->title = 'Редактирование настроек профиля';
?>
<section class="account__redaction-wrapper">
    <h1>Редактирование настроек профиля</h1>
    <form enctype="multipart/form-data" id="account" method="post">
        <?php $form = ActiveForm::begin([
            'enableClientScript' => false,
            'options' => [
                'enctype'=>'multipart/form-data',
            ]
        ]); ?>
        <div class="account__redaction-section">
            <h3 class="div-line">Настройки аккаунта</h3>
            <div class="account__redaction-section-wrapper">
                <div class="account__redaction-avatar">
                    <img src="<?=$model->photo?>" width="156" height="156">
                    <?=Html::activeFileInput($model, 'photo', ['id' => 'upload-avatar'])?>
                    <label for="upload-avatar" class="link-regular">Сменить аватар</label>
                </div>
                <div class="account__redaction">
                    <div class="account__input account__input--name">
                        <label for="200">Ваше имя</label>
                        <?=Html::activeTextInput($model, 'name', ['class' => 'input textarea'])?>
                        <?php if($model->hasErrors('name')) :
                            foreach ($model->getErrors('name') as $error) :?>
                                <span style="color:red;"><?=$error?></span>
                            <?php endforeach;
                        endif; ?>
                    </div>
                    <div class="account__input account__input--email">
                        <label for="201">email</label>
                        <?=Html::activeTextInput($model, 'email', ['class' => 'input textarea'])?>
                        <?php if($model->hasErrors('email')) :
                            foreach ($model->getErrors('email') as $error) :?>
                                <span style="color:red;"><?=$error?></span>
                            <?php endforeach;
                        endif; ?>
                    </div>
                    <div class="account__input account__input--name">
                        <label for="202">Город</label>
                        <?= Html::activeDropDownList($model, 'city_id', $model->getCityList(),
                            ['class' => 'multiple-select input multiple-select-big', 'size' => 1,
                                'options' => [
                                    $model->city_id => ['selected' => true]
                            ]]) ?>
                    </div>
                    <div class="account__input account__input--date">
                        <label for="203">День рождения</label>
                        <?=Html::activeTextInput($model, 'birthday', ['class' => 'input-middle input input-date', 'type' => 'date'])?>
                        <?php if($model->hasErrors('birthday')) :
                            foreach ($model->getErrors('birthday') as $error) :?>
                                <span style="color:red;"><?=$error?></span>
                            <?php endforeach;
                        endif; ?>
                    </div>
                    <div class="account__input account__input--info">
                        <label for="204">Информация о себе</label>
                        <?=Html::activeTextarea($model, 'about', ['class' => 'input textarea', 'rows' => 7])?>
                        <?php if($model->hasErrors('about')) :
                            foreach ($model->getErrors('about') as $error) :?>
                                <span style="color:red;"><?=$error?></span>
                            <?php endforeach;
                        endif; ?>
                    </div>
                </div>
            </div>
            <h3 class="div-line">Выберите свои специализации</h3>
            <div class="account__redaction-section-wrapper">
                <div class="search-task__categories account_checkbox--bottom">
                    <?= Html::activeCheckboxList($model, 'specialization', $model->getSpecializationList(),
                        [
                            'item' => function($index, $label, $name, $checked, $value) use ($model) {
                                $spec = !empty($model->specialization) ? $model->specialization : [];
                                $checked = in_array($value, $spec) ? 'checked' : '';
                                $return = '<input class="visually-hidden checkbox__input" type="checkbox" name="' . $name . '" value="' . $value . '" id="' . $value . '" ' . $checked . '>';
                                $return .= '<label class="checkbox" for="' . $value . '">' . $label . '</label>';
                                return $return;
                            },
                            'tag' => false]
                    ) ?>
                </div>
            </div>
            <h3 class="div-line">Безопасность</h3>
            <div class="account__redaction-section-wrapper account__redaction">
                <div class="account__input">
                    <label for="211">Новый пароль</label>
                    <?=Html::activePasswordInput($model, 'password', ['class' => 'input textarea'])?>
                    <?php if($model->hasErrors('password')) :
                        foreach ($model->getErrors('password') as $error) :?>
                            <span style="color:red;"><?=$error?></span>
                        <?php endforeach;
                    endif; ?>
                </div>
                <div class="account__input">
                    <label for="212">Повтор пароля</label>
                    <?=Html::activePasswordInput($model, 'passwordRepeat', ['class' => 'input textarea'])?>
                    <?php if($model->hasErrors('passwordRepeat')) :
                        foreach ($model->getErrors('passwordRepeat') as $error) :?>
                            <span style="color:red;"><?=$error?></span>
                        <?php endforeach;
                    endif; ?>
                </div>
            </div>

            <h3 class="div-line">Фото работ</h3>

            <div class="account__redaction-section-wrapper account__redaction">
                <span class="dropzone">Выбрать фотографии</span>
            </div>

            <h3 class="div-line">Контакты</h3>
            <div class="account__redaction-section-wrapper account__redaction">
                <div class="account__input">
                    <label for="213">Телефон</label>
                    <?=Html::activeTextInput($model, 'phone', ['class' => 'input textarea'])?>
                    <?php if($model->hasErrors('phone')) :
                        foreach ($model->getErrors('phone') as $error) :?>
                            <span style="color:red;"><?=$error?></span>
                        <?php endforeach;
                    endif; ?>
                </div>
                <div class="account__input">
                    <label for="214">Skype</label>
                    <?=Html::activeTextInput($model, 'skype', ['class' => 'input textarea'])?>
                    <?php if($model->hasErrors('skype')) :
                        foreach ($model->getErrors('skype') as $error) :?>
                            <span style="color:red;"><?=$error?></span>
                        <?php endforeach;
                    endif; ?>
                </div>
                <div class="account__input">
                    <label for="215">Telegram</label>
                    <?=Html::activeTextInput($model, 'telegram', ['class' => 'input textarea'])?>
                    <?php if($model->hasErrors('telegram')) :
                        foreach ($model->getErrors('telegram') as $error) :?>
                            <span style="color:red;"><?=$error?></span>
                        <?php endforeach;
                    endif; ?>
                </div>
            </div>
            <h3 class="div-line">Настройки сайта</h3>
            <h4>Уведомления</h4>
            <div class="account__redaction-section-wrapper account_section--bottom">
                <div class="search-task__categories account_checkbox--bottom">
                    <?=Html::activeCheckbox($model, 'setting_new_message', ['class' => 'visually-hidden checkbox__input', 'label' => false])?>
                    <?=Html::activeLabel($model, 'setting_new_message')?>

                    <?=Html::activeCheckbox($model, 'setting_action_task', ['class' => 'visually-hidden checkbox__input', 'label' => false])?>
                    <?=Html::activeLabel($model, 'setting_action_task')?>

                    <?=Html::activeCheckbox($model, 'setting_new_review', ['class' => 'visually-hidden checkbox__input', 'label' => false])?>
                    <?=Html::activeLabel($model, 'setting_new_review')?>
                </div>
                <div class="search-task__categories account_checkbox account_checkbox--secrecy">
                    <?=Html::activeCheckbox($model, 'setting_show_contact', ['class' => 'visually-hidden checkbox__input', 'label' => false])?>
                    <?=Html::activeLabel($model, 'setting_show_contact')?>

                    <?=Html::activeCheckbox($model, 'setting_hide_profile', ['class' => 'visually-hidden checkbox__input', 'label' => false])?>
                    <?=Html::activeLabel($model, 'setting_hide_profile')?>
                </div>
            </div>
        </div>
        <?= Html::submitButton('Сохранить изменения', ['class' => 'button']) ?>
        <?php ActiveForm::end(); ?>
</section>
