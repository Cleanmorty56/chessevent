<?php
$this->registerCssFile("@web/css/sign.css");
use yii\helpers\Html;
use yii\widgets\ActiveForm; ?>

<div class="containereg">
    <h2 style="text-align: center;">Регистрация</h2>
    <?php
    $items = \app\models\Region::find()
        ->select(['name'])
        ->indexBy('id')
        ->column();

    $form = ActiveForm::begin([
        'options' => [
            'enctype' => 'multipart/form-data',
            'id' => 'signup-form',
            'class' => 'pawn-form',
        ]
    ]) ?>

    <div id="pawn-container" style="height: 50px; margin: 20px 0; position: relative;">
        <div id="pawn" style="position: absolute; left: 0; top: 0; font-size: 30px; transition: all 0.5s ease; z-index: 100; color: black;">♙</div>
    </div>

    <div class="form-fields">
        <?= $form->field($model, 'username')->textInput(['class' => 'form-input']) ?>
        <?= $form->field($model, 'first_name')->textInput(['class' => 'form-input']) ?>
        <?= $form->field($model, 'last_name')->textInput(['class' => 'form-input']) ?>
        <?= $form->field($model, 'email')->textInput(['class' => 'form-input']) ?>
        <?= $form->field($model, 'elo')->textInput(['class' => 'form-input']) ?>
        <?= $form->field($model, 'region_id')->dropDownList(
            $items, ['prompt' => 'Выберите регион', 'class' => 'form-input']
        ) ?>
        <?= $form->field($model, 'password')->passwordInput(['class' => 'form-input']) ?>
        <?= $form->field($model, 'password_repeat')->passwordInput(['class' => 'form-input']) ?>
    </div>

    <div class="form-group">
        <div class="col-lg-offset-1 col-lg-11">
            <?= Html::submitButton('Зарегистрироваться', ['class' => 'btn-reg']) ?>
        </div>
    </div>
    <?php ActiveForm::end() ?>
</div>


<script>
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.getElementById('signup-form');
        const pawn = document.getElementById('pawn');
        const inputs = form.querySelectorAll('.form-input');

        function movePawnToInput(input) {
            const rect = input.getBoundingClientRect();
            const formRect = form.getBoundingClientRect();

            const leftPosition = rect.left - formRect.left - 35;
            const topPosition = rect.top - formRect.top + (rect.height / 2);

            pawn.style.left = leftPosition + 'px';
            pawn.style.top = topPosition + 'px';


            pawn.style.transform = 'translateY(-50%) translateY(-10px)';
            setTimeout(() => {
                pawn.style.transform = 'translateY(-50%)';
            }, 200);
        }


        inputs.forEach(input => {
            input.addEventListener('focus', function() {
                movePawnToInput(input);
            });
            input.addEventListener('click', function() {
                movePawnToInput(input);
            });
        });
        form.addEventListener('submit', function() {
            pawn.style.opacity = '0';
            pawn.style.transition = 'opacity 0.5s ease';
        });

        if (inputs.length > 0) {
            movePawnToInput(inputs[0]);
        }
    });
</script>

