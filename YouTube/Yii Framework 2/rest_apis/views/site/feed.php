<?php

/** @var yii\web\View $this */

$this->title = 'Notícias';
?>
<div class="site-index">

    <div class="body-content">

        <h1>Feed de Notícias via REST API</h1>
    <hr>
        <?php foreach($data as $row) : ?>
            <p>ID <?= $row['id'] ?></p>
            <p>Título <?= $row['title'] ?></p>
            <p>Status <?= $row['status'] ?></p>
            <hr>
        <?php endforeach; ?>
    </div>
</div>
