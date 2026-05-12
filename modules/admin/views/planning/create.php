<?php

use yii\helpers\Html;
$this->registerCssFile("@web/css/profile.css");

/** @var yii\web\View $this */
/** @var app\models\Planning $model */

$this->title = 'Create Planning';
$this->params['breadcrumbs'][] = ['label' => 'Plannings', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="planning-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
