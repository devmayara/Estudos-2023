<?php
use \yii\bootstrap5\ActiveForm;
use yii\helpers\Html;
/** @var yii\web\View $this */
?>
<h1>Formulário de Cadastro</h1>
<hr>

<?php $form = ActiveForm::begin() ?>

    <?= $form->field($model, 'nome') ?>
    <?= $form->field($model, 'email') ?>
    <?= $form->field($model, 'idade') ?>

    <div class="form-group">
        <? Html::submitButton('Enviar Dados', ['class'=>'btn btn-primary']) ?>
    </div>

<?php ActiveForm::end() ?>
