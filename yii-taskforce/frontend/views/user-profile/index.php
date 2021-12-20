<?php
/**
 * @var $model \common\models\User
 * @var $this \yii\web\View
 */
use yii\helpers\Html;

$this->title = 'Редактирование настроек профиля';
?>
<section class="account__redaction-wrapper">
    <h1>Редактирование настроек профиля</h1>
    <form enctype="multipart/form-data" id="account" method="post">
        <div class="account__redaction-section">
            <h3 class="div-line">Настройки аккаунта</h3>
            <div class="account__redaction-section-wrapper">
                <div class="account__redaction-avatar">
                    <img src="<?=$model->photo?>" width="156" height="156">
                    <?=Html::activeFileInput($model, 'file', ['id' => 'upload-avatar'])?>
                    <label for="upload-avatar" class="link-regular">Сменить аватар</label>
                </div>
                <div class="account__redaction">
                    <div class="account__input account__input--name">
                        <label for="200">Ваше имя</label>
                        <?=Html::activeTextInput($model, 'name', ['class' => 'input textarea'])?>
                    </div>
                    <div class="account__input account__input--email">
                        <label for="201">email</label>
                        <?=Html::activeTextInput($model, 'email', ['class' => 'input textarea'])?>
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
                    </div>
                    <div class="account__input account__input--info">
                        <label for="204">Информация о себе</label>
                        <?=Html::activeTextarea($model, 'about', ['class' => 'input textarea', 'rows' => 7])?>
                    </div>
                </div>
            </div>
            <h3 class="div-line">Выберите свои специализации</h3>
            <div class="account__redaction-section-wrapper">
                <div class="search-task__categories account_checkbox--bottom">
                    <?= Html::activeCheckboxList($model, 'specialization', $model->getSpecializationList(),
                        [
                            'item' => function($index, $label, $name, $checked, $value) use ($model) {
                                $checked = in_array($value, $model->specialization) ? 'checked' : '';
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
                </div>
                <div class="account__input">
                    <label for="212">Повтор пароля</label>
                    <?=Html::activePasswordInput($model, 'passwordRepeat', ['class' => 'input textarea'])?>
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
                    <input class="input textarea" type="tel" id="213" name="" placeholder="8 (555) 187 44 87">
                </div>
                <div class="account__input">
                    <label for="214">Skype</label>
                    <input class="input textarea" type="password" id="214" name="" placeholder="DenisT">
                </div>
                <div class="account__input">
                    <label for="215">Telegram</label>
                    <input class="input textarea" id="215" name="" placeholder="@DenisT">
                </div>
            </div>
            <h3 class="div-line">Настройки сайта</h3>
            <h4>Уведомления</h4>
            <div class="account__redaction-section-wrapper account_section--bottom">
                <div class="search-task__categories account_checkbox--bottom">
                    <input class="visually-hidden checkbox__input" id="216" type="checkbox" name="" value="" checked>
                    <label for="216">Новое сообщение</label>
                    <input class="visually-hidden checkbox__input" id="217" type="checkbox" name="" value="" checked>
                    <label for="217">Действия по заданию</label>
                    <input class="visually-hidden checkbox__input" id="218" type="checkbox" name="" value="" checked>
                    <label for="218">Новый отзыв</label>
                </div>
                <div class="search-task__categories account_checkbox account_checkbox--secrecy">
                    <input class="visually-hidden checkbox__input" id="219" type="checkbox" name="" value="">
                    <label for="219">Показывать мои контакты только заказчику</label>
                    <input class="visually-hidden checkbox__input" id="220" type="checkbox" name="" value="" checked>
                    <label for="220">Не показывать мой профиль</label>
                </div>
            </div>
        </div>
        <button class="button" type="submit">Сохранить изменения</button>
    </form>
</section>
