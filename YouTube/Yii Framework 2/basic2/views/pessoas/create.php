<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\Pessoas $model */

$this->title = 'Create Pessoas';
$this->params['breadcrumbs'][] = ['label' => 'Pessoas', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="pessoas-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
