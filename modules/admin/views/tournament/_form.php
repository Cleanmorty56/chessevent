<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
$this->registerCssFile("@web/css/profile.css");

/** @var yii\web\View $this */
/** @var app\models\Tournament $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="tournament-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'img')->fileInput() ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'description')->textarea(['rows' => 4]) ?>

    <?= $form->field($model, 'location')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'quantity_rounds')->textInput([
        'type' => 'number',
        'min' => 1,
        'max' => 14,
        'step' => 1
    ]) ?>

    <?= $form->field($model, 'gamemode_id')->dropDownList(
        $items,
        ['prompt' => 'Выберите игровой режим']
    ) ?>

    <?= $form->field($model, 'level_id')->dropDownList(
        $levels,
        ['prompt' => 'Выберите уровень турнира']
    ) ?>

    <?= $form->field($model, 'status')->dropDownList([
        'Запланирован' => 'Запланирован',
        'В процессе' => 'В процессе',
        'Завершен' => 'Завершен'
    ]) ?>

    <div class="form-group">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
        <?= Html::a('Отмена', ['view', 'id' => $model->id], ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>