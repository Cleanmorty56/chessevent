<?php

use yii\helpers\Html;
$this->registerCssFile("@web/css/profile.css");
/** @var yii\web\View $this */
/** @var app\models\Tournament $model */

$this->title = 'Добавление турнира';
$this->params['breadcrumbs'][] = ['label' => 'Tournaments', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="tournament-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'items' => $items,
        'levels' => $levels,
    ]) ?>

</div>
