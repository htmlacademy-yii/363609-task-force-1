<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model \frontend\models\form\RegistrationForm */
/* @var $errors array */
?>
<section class="registration__user">
    <h1>Регистрация аккаунта</h1>
    <div class="registration-wrapper">
        <?php $form = ActiveForm::begin([
            'options' => [
                'class' => 'registration__user-form form-create'
            ]
        ]); ?>
            <?=Html::activeLabel($model, 'email', ['class' => isset($errors['email'])?'input-danger':''])?>
            <?=Html::activeTextInput($model, 'email', ['class' => 'input textarea', 'placeholder' => 'em@mail.ru'])?>
            <?php if(isset($errors['email'])) {
                foreach ($errors['email'] as $error) {?>
                <span style="color:red;"><?=$error?></span>
            <?php }
            } ?>
            <?=Html::activeLabel($model, 'name', ['class' => isset($errors['name'])?'input-danger':''])?>
            <?=Html::activeTextInput($model, 'name', ['class' => 'input textarea', 'placeholder' => 'Андрей Петров'])?>
            <?php if(isset($errors['name'])) {
                foreach ($errors['name'] as $error) {?>
                    <span style="color:red;"><?=$error?></span>
                <?php }
            } ?>
            <?=Html::activeLabel($model, 'city', ['class' => isset($errors['city'])?'input-danger':''])?>
            <?=Html::activeDropDownList($model, 'city', $model->getCitiesList(), ['class' => 'multiple-select input town-select registration-town', 'size' => 1])?>
            <?php if(isset($errors['city'])) {
                foreach ($errors['city'] as $error) {?>
                    <span style="color:red;"><?=$error?></span>
                <?php }
            } ?>
            <?=Html::activeLabel($model, 'password', ['class' => isset($errors['password'])?'input-danger':''])?>
            <?=Html::activePasswordInput($model, 'password', ['class' => 'input textarea'])?>
            <?php if(isset($errors['password'])) {
                foreach ($errors['password'] as $error) {?>
                    <span style="color:red;"><?=$error?></span>
                <?php }
            } ?>
            <?=Html::submitButton('Cоздать аккаунт', ['class' => 'button button__registration'])?>
        <?php ActiveForm::end(); ?>
    </div>
</section>
