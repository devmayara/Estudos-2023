<?php
/** @var yii\web\View $this */

use yii\bootstrap5\Html;
use yii\widgets\ActiveForm;

?>

<div class="site-index">
    <div class="jumbotron">
        <h1>Uploads de Arquivos</h1>
        <hr>
    </div>

    <div class="body-content">
        <?php $form = ActiveForm::begin() ?>
            <?= $form->field($model, 'nome') ?>
            <?= $form->field($model, 'fotoCliente')->fileInput() ?>

            <br>
            <?= Html::submitButton('Salvar', ['class' => 'btn btn-primary']) ?>
        <?php ActiveForm::end() ?>
    </div>
</div>
