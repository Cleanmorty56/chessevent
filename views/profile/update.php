<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

$this->title = 'Редактирование профиля';
$this->params['breadcrumbs'][] = ['label' => 'Профиль', 'url' => ['profile/profile']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="profile-update-container">
    <div class="profile-update-card">
        <h1><i class="fas fa-user-edit"></i> <?= Html::encode($this->title) ?></h1>

        <?php $form = ActiveForm::begin([
            'id' => 'profile-update-form',
            'options' => ['class' => 'profile-form'],
        ]); ?>

        <div class="form-section">
            <h3><i class="fas fa-user-circle"></i> Личная информация</h3>

            <?= $form->field($model, 'first_name')->textInput([
                'maxlength' => true,
                'placeholder' => 'Введите ваше имя',
                'class' => 'form-control'
            ])->label('Имя') ?>

            <?= $form->field($model, 'last_name')->textInput([
                'maxlength' => true,
                'placeholder' => 'Введите вашу фамилию',
                'class' => 'form-control'
            ])->label('Фамилия') ?>
        </div>

        <div class="form-section">
            <h3><i class="fas fa-envelope"></i> Контактная информация</h3>

            <?= $form->field($model, 'email')->textInput([
                'maxlength' => true,
                'placeholder' => 'example@mail.com',
                'class' => 'form-control'
            ])->label('Email') ?>
        </div>

        <div class="form-buttons">
            <?= Html::submitButton('<i class="fas fa-save"></i> Сохранить изменения', [
                'class' => 'btn btn-save',
                'name' => 'save-button'
            ]) ?>

            <?= Html::a('<i class="fas fa-times"></i> Отмена', ['profile/profile'], [
                'class' => 'btn btn-cancel'
            ]) ?>
        </div>

        <?php ActiveForm::end(); ?>
    </div>
</div>

<style>
    .profile-update-container {
        max-width: 700px;
        margin: 0 auto;
        padding: 40px 20px;
        background: #f5f0e8;
        min-height: 100vh;
    }

    .profile-update-card {
        background: white;
        border-radius: 20px;
        padding: 35px;
        border: 2px solid #8b4513;
        box-shadow: 0 10px 30px rgba(139, 69, 19, 0.1);
    }

    .profile-update-card h1 {
        color: #8b4513;
        font-size: 28px;
        margin-bottom: 30px;
        padding-bottom: 15px;
        border-bottom: 2px solid #8b4513;
        text-align: center;
    }

    .form-section {
        margin-bottom: 30px;
        padding: 20px;
        background: #faf5eb;
        border-radius: 15px;
    }

    .form-section h3 {
        color: #8b4513;
        font-size: 18px;
        margin-bottom: 20px;
        padding-bottom: 5px;
        border-bottom: 1px solid #e0d0b0;
    }

    .form-group {
        margin-bottom: 20px;
    }

    .form-group label {
        font-weight: bold;
        color: #5d4037;
        margin-bottom: 8px;
        display: block;
    }

    .form-control {
        width: 100%;
        border: 2px solid #e0d0b0;
        border-radius: 10px;
        padding: 12px 15px;
        font-size: 16px;
        transition: all 0.3s;
    }

    .form-control:focus {
        border-color: #8b4513;
        outline: none;
        box-shadow: 0 0 0 3px rgba(139, 69, 19, 0.1);
    }

    .form-buttons {
        display: flex;
        gap: 15px;
        justify-content: center;
        margin-top: 30px;
    }

    .btn-save {
        background: #8b4513;
        color: white;
        border: none;
        padding: 12px 30px;
        border-radius: 10px;
        font-size: 16px;
        font-weight: bold;
        cursor: pointer;
        transition: all 0.3s;
    }

    .btn-save:hover {
        background: #6b3100;
        transform: translateY(-2px);
    }

    .btn-cancel {
        background: #e0e0e0;
        color: #666;
        border: none;
        padding: 12px 30px;
        border-radius: 10px;
        font-size: 16px;
        font-weight: bold;
        text-decoration: none;
        transition: all 0.3s;
        display: inline-block;
        text-align: center;
    }

    .btn-cancel:hover {
        background: #ccc;
        color: #444;
        text-decoration: none;
    }

    .help-block {
        color: #c62828;
        font-size: 12px;
        margin-top: 5px;
    }

    @media (max-width: 600px) {
        .profile-update-card {
            padding: 20px;
        }

        .form-buttons {
            flex-direction: column;
        }

        .btn-save, .btn-cancel {
            width: 100%;
            text-align: center;
        }
    }
</style>