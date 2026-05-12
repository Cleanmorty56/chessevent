<?php
use yii\bootstrap5\ActiveForm;
use yii\bootstrap5\Html;
?>
<div class="container-ch">
    <?php $form = ActiveForm::begin(); ?>

    <div class="form-group">
        <?= $form->field($model, 'oldPassword')->passwordInput(['maxLength' => true]) ?>
    </div>

    <div class="form-group">
        <?= $form->field($model, 'newPassword')->passwordInput(['maxLength' => true]) ?>
    </div>

    <div class="form-group">
        <?= $form->field($model, 'newPasswordRepeat')->passwordInput(['maxLength' => true]) ?>
    </div>

    <div class="form-group">
        <?= Html::submitButton('Изменить пароль', ['class' => 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>
</div>

<style>
    body {
        margin: 0;
        display: flex;
        flex-direction: column;
        min-height: 100vh;
    }

    main {
        flex: 1;
    }

    .container-ch {
        max-width: 500px;
        margin: 2rem auto;
        padding: 2rem;
        background-color: #f0d9b5;
        border-radius: 8px;
        border: 3px solid #8b4513;
        box-shadow: 0 4px 12px rgba(139, 69, 19, 0.2);
        position: relative;
    }

    /* Фоновый узор - теперь не перекрывает содержимое */
    .container-ch::before {
        content: "";
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background-image:
                linear-gradient(45deg, #b58863 25%, transparent 25%),
                linear-gradient(-45deg, #b58863 25%, transparent 25%),
                linear-gradient(45deg, transparent 75%, #b58863 75%),
                linear-gradient(-45deg, transparent 75%, #b58863 75%);
        background-size: 40px 40px;
        background-position: 0 0, 0 20px, 20px -20px, -20px 0px;
        opacity: 0.1;
        pointer-events: none; /* КЛЮЧЕВОЕ: фон не перехватывает клики */
        z-index: 0;
        border-radius: 8px;
    }

    /* Все содержимое - выше фона */
    .container-ch form {
        position: relative;
        z-index: 1;
    }

    .form-group {
        margin-bottom: 1.5rem;
        position: relative;
        z-index: 1;
    }

    /* Стили для полей ввода */
    .form-group .form-control {
        background-color: #fff9f0;
        border: 2px solid #8b4513;
        border-radius: 4px;
        padding: 0.75rem 1rem;
        font-family: 'Georgia', serif;
        color: #5a3921;
        transition: all 0.3s ease;
        width: 100%;
        box-sizing: border-box;
    }

    .form-group .form-control:focus {
        border-color: #6b3100;
        box-shadow: 0 0 0 0.25rem rgba(139, 69, 19, 0.25);
        outline: none;
        background-color: #ffffff;
    }

    /* Стили для label */
    .form-group label.control-label {
        display: block;
        margin-bottom: 0.5rem;
        color: #5a3921;
        font-weight: bold;
        font-family: 'Georgia', serif;
    }

    /* Стили для кнопки */
    .btn-primary {
        background-color: #8b4513;
        border-color: #6b3100;
        color: white;
        padding: 0.75rem 1.5rem;
        font-size: 1rem;
        font-weight: bold;
        border-radius: 4px;
        text-transform: uppercase;
        letter-spacing: 1px;
        transition: all 0.3s ease;
        width: 100%;
        margin-top: 1rem;
        cursor: pointer;
        border: none;
    }

    .btn-primary:hover {
        background-color: #6b3100;
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
    }

    .btn-primary:active {
        transform: translateY(0);
    }

    /* Сообщения об ошибках */
    .help-block {
        color: #d9534f;
        font-size: 0.875rem;
        margin-top: 0.25rem;
        font-style: italic;
    }

    /* Убираем стандартные стили бутстрап если мешают */
    .form-group .form-control:focus {
        outline: none;
    }

    @media (max-width: 576px) {
        .container-ch {
            padding: 1.5rem;
            margin: 1rem;
        }

        .form-group .form-control {
            padding: 0.5rem 0.75rem;
        }

        .btn-primary {
            padding: 0.6rem 1rem;
        }
    }
</style>