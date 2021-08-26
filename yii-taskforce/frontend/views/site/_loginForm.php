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
    <?php if(!empty($model->getErrors()['email'])) {?>
        <span style="color:red; padding-bottom: 10px"><?=$model->getErrors()['email'][0]?></span>
    <?php } ?>
</p>
<p>
    <?=Html::activeLabel($model, 'password', ['class' => 'form-modal-description'])?>
    <?=Html::activePasswordInput($model, 'password', ['class' => 'enter-form-email input input-middle'])?>
    <?php if(!empty($model->getErrors()['password'])) {?>
        <span style="color:red; padding-bottom: 10px"><?=$model->getErrors()['password'][0]?></span>
    <?php } ?>
</p>
<?=Html::submitButton('Войти', ['class' => 'button'])?>
<?php ActiveForm::end()?>
