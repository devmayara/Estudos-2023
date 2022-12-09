<?php
use yii\bootstrap5\Html;
?>

<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?= $this->registerMetaTag(['name' => 'viewport', 'content' => 'width=device, initial-scale=1']) ?>
    <?php $this->head() ?>
</head>
<body>
<?php $this->beginBody() ?>

    <h1>ÁREA FINANCEIRA</h1>
    <hr>
    <div class="container">
        <?= $content ?>
    </div>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
