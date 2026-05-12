<?php

use yii\bootstrap5\ActiveForm;
use yii\bootstrap5\Html;
use yii\helpers\Url;

$this->registerCssFile("@web/css/index.css") ?>


<!doctype html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Главная</title>
</head>
<body style="overflow-x: hidden; !important;">
<div class="container-fluid p-0">
    <div class="bannernepo">
        <div class="banner-content">
            <h1 class="titlenepo">Попробуй свои силы!</h1>
            <a href="<?= Url::to(['site/tournaments']) ?>" class="btn btn-primary btn-lg"
               style="background-color: #646560; border: none; padding: 12px 40px; font-size: 1.2rem; font-weight: 600;">
                Принять участие!
            </a>
        </div>
    </div>
</div>
<div class="containerspeaker">
    <div class="quote-box">
        <p>«Вы можете узнать гораздо больше из проигранной игры, чем от выигранной. Вы должны проиграть сотни игр,
            прежде чем стать хорошим игроком»</p>
    </div>
    <div class="author-image-container">
       <?= Html::img('@web/img/e4d76254bf6553bb9eec73d08600f50f.png', ['alt' => 'Хосе Рауль Капабланка', 'class' => 'author-image']) ?>
        <p class="author-name">Хосе Рауль Капабланка</p>
    </div>
</div>
<div class="block_kon">
    <div class="form">
        <div class="title">
            <h2>Заявка на планирование турнира</h2>
        </div>
        <div class="form-content">
            <?php
            $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]) ?>
            <?= $form->field($model, 'content')->textarea(['class' => 'info']) ?>
            <?= $form->field($model, 'organizer')->textInput(['class' => 'info2']) ?>
            <div class="mal">
                <?= $form->field($model, 'gamemode_id')->dropDownList(
                    $items, ['prompt' => 'Выберите игровой режим']
                ) ?>
                <?= $form->field($model, 'quantity_rounds')->textInput(['class' => 'info1']) ?>
                <?= $form->field($model, 'imageFile')->fileInput(['class' => 'info1']) ?>
            </div>
        </div>
        <div class="form-group">
            <div class="btnzajavka">
                <?= Html::submitButton('Отправить заявку', ['class' => 'btn-top']) ?>
            </div>
        </div>
        <?php ActiveForm::end() ?>
    </div>
</div>
</body>
</html>