<?php
use yii\widgets\ActiveForm;
use yii\helpers\Html;
use yii\helpers\Url;

/**
 * @var $model \common\models\LoginForm
 */
?>
<?php ActiveForm::begin([
    'action' => ['site/login'],
    'options' => ['data-pjax' => true],
]); ?>
<p>
    <?=Html::activeLabel($model, 'email', ['class' => 'form-modal-description'])?>
    <?=Html::activeTextInput($model, 'email', ['class' => 'enter-form-email input input-middle', 'type' => 'email'])?>
    <?php if($model->hasErrors('email')) :?>
        <span style="color:red; padding-bottom: 10px"><?=$model->getFirstError('email')?></span>
    <?php endif; ?>
</p>
<p>
    <?=Html::activeLabel($model, 'password', ['class' => 'form-modal-description'])?>
    <?=Html::activePasswordInput($model, 'password', ['class' => 'enter-form-email input input-middle'])?>
    <?php if($model->hasErrors('password')) :?>
        <span style="color:red; padding-bottom: 10px"><?=$model->getFirstError('password')?></span>
    <?php endif; ?>
</p>
<?=Html::submitButton('Войти', ['class' => 'button'])?>
<?= yii\authclient\widgets\AuthChoice::widget([
    'baseAuthUrl' => ['site/login-vk'],
    'popupMode' => true,
]) ?>
<?php ActiveForm::end()?>
