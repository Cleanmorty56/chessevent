<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
$this->registerCssFile("@web/css/profile.css");

/** @var yii\web\View $this */
/** @var app\models\PlanningSearch $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="planning-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'content') ?>

    <?= $form->field($model, 'organizer') ?>

    <?= $form->field($model, 'user_id') ?>

    <?= $form->field($model, 'gamemode_id') ?>

    <?php // echo $form->field($model, 'imageFile') ?>

    <?php // echo $form->field($model, 'quantity_rounds') ?>

    <?php // echo $form->field($model, 'created_at') ?>

    <?php // echo $form->field($model, 'updated_at') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
