<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
$this->registerCssFile("@web/css/profile.css");

/** @var yii\web\View $this */
/** @var app\models\Planning $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="planning-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'content')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'organizer')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'user_id')->textInput() ?>

    <?= $form->field($model, 'gamemode_id')->textInput() ?>

    <?= $form->field($model, 'imageFile')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'quantity_rounds')->textInput() ?>


    <div class="form-group">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-chesses']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
