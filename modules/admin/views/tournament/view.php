<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
$this->registerCssFile("@web/css/profile.css");
/** @var yii\web\View $this */
/** @var app\models\Tournament $model */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Tournaments', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="tournament-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Изменить', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Управление', ['manage', 'id' => $model->id], ['class' => 'btn btn-success']) ?>
        <?= Html::a('Удалить', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Удалить турнир?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'img',
            'name',
            'description',
            [
                'attribute' => 'gamemode_id',
                'value' => function($model) {
                    return $model->gamemode->name;
                },
            ],
            'location',
            'quantity_rounds',
            'status',
            [
                'attribute' => 'level_id',
                'value' => function($model) {
                    return $model->level->name;
                },
            ],
        ],
    ]) ?>

</div>
