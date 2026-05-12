<?php

use yii\helpers\Html;
$this->registerCssFile("@web/css/profile.css");
/** @var yii\web\View $this */
/** @var app\models\Planning $model */

$this->title = 'Редактировние: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Plannings', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="planning-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
