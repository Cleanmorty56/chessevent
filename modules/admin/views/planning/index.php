<?php

use app\models\Planning;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;
$this->registerCssFile("@web/css/profile.css");

/** @var yii\web\View $this */
/** @var app\models\PlanningSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Заявки на планирование турнира';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="planning-index chess-theme">

    <h1 class="chess-main-title"><?= Html::encode($this->title) ?></h1>

    <!-- КАРТОЧКИ ДЛЯ ВСЕХ УСТРОЙСТВ -->
    <div class="planning-cards">
        <?php foreach ($dataProvider->getModels() as $model): ?>
            <div class="planning-card <?= $model->status == Planning::STATUS_APPROVED ? 'card-approved' : ($model->status == Planning::STATUS_REJECTED ? 'card-rejected' : 'card-pending') ?>">
                <div class="planning-card-header">
                    <div class="planning-id">Заявка #<?= $model->id ?></div>
                    <div class="planning-status">
                        <?php
                        $statusText = Planning::getStatuses()[$model->status];
                        $statusClass = '';
                        if ($model->status == Planning::STATUS_APPROVED) {
                            $statusClass = 'status-approved';
                        } elseif ($model->status == Planning::STATUS_REJECTED) {
                            $statusClass = 'status-rejected';
                        } else {
                            $statusClass = 'status-pending';
                        }
                        ?>
                        <span class="status-badge <?= $statusClass ?>"><?= Html::encode($statusText) ?></span>
                    </div>
                </div>
                
                <div class="planning-card-body">
                    <div class="planning-field">
                        <span class="field-label">📋 Содержание:</span>
                        <span class="field-value"><?= Html::encode($model->content) ?></span>
                    </div>
                    
                    <div class="planning-field">
                        <span class="field-label">👤 Организатор:</span>
                        <span class="field-value"><?= Html::encode($model->organizer) ?></span>
                    </div>
                    
                    <div class="planning-field">
                        <span class="field-label">👨‍💻 Пользователь:</span>
                        <span class="field-value"><?= Html::encode($model->user->username ?? '—') ?></span>
                    </div>
                    
                    <div class="planning-field">
                        <span class="field-label">🎮 Режим игры:</span>
                        <span class="field-value"><?= Html::encode($model->gamemode->name ?? '—') ?></span>
                    </div>
                    
                    <div class="planning-field">
                        <span class="field-label">🔄 Количество туров:</span>
                        <span class="field-value"><?= $model->quantity_rounds ?></span>
                    </div>
                    
                    <div class="planning-field">
                        <span class="field-label">📅 Дата создания:</span>
                        <span class="field-value"><?= Yii::$app->formatter->asDatetime($model->created_at) ?></span>
                    </div>
                </div>
                
                <div class="planning-card-footer">
                    <?= Html::a('👁️ Просмотр', ['view', 'id' => $model->id], ['class' => 'btn-card-view']) ?>
                    
                    <?php if ($model->status != Planning::STATUS_APPROVED): ?>
                        <?= Html::a('✅ Одобрить', ['approve', 'id' => $model->id], [
                            'class' => 'btn-card-approve',
                            'data' => [
                                'confirm' => 'Вы уверены, что хотите одобрить эту заявку?',
                                'method' => 'post',
                            ],
                        ]) ?>
                    <?php endif; ?>
                    
                    <?php if ($model->status != Planning::STATUS_REJECTED): ?>
                        <?= Html::a('❌ Отклонить', ['reject', 'id' => $model->id], [
                            'class' => 'btn-card-reject',
                            'data' => [
                                'confirm' => 'Вы уверены, что хотите отклонить эту заявку?',
                                'method' => 'post',
                            ],
                        ]) ?>
                    <?php endif; ?>
                    
                    <?= Html::a('🗑️ Удалить', ['delete', 'id' => $model->id], [
                        'class' => 'btn-card-delete',
                        'data' => [
                            'confirm' => 'Вы уверены, что хотите удалить эту заявку?',
                            'method' => 'post',
                        ],
                    ]) ?>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

</div>

<style>
    .chess-theme {
        font-family: 'Georgia', serif;
        background-color: #f8f1e5;
        padding: 25px;
        border-radius: 10px;
        box-shadow: 0 0 15px rgba(139, 69, 19, 0.1);
        margin: 20px auto;
        max-width: 1400px;
    }

    .chess-main-title {
        color: #8b4513;
        text-align: center;
        margin-bottom: 25px;
        font-weight: bold;
        text-shadow: 1px 1px 2px rgba(0,0,0,0.1);
        padding-bottom: 10px;
        border-bottom: 2px solid #8b4513;
    }

    /* СЕТКА КАРТОЧЕК */
    .planning-cards {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(380px, 1fr));
        gap: 25px;
    }

    /* КАРТОЧКА ЗАЯВКИ */
    .planning-card {
        background: white;
        border: 2px solid #8b4513;
        border-radius: 15px;
        overflow: hidden;
        transition: all 0.3s;
        box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    }

    .planning-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 25px rgba(139, 69, 19, 0.3);
    }

    /* ЦВЕТА КАРТОЧЕК ПО СТАТУСУ */
    .card-approved {
        border-left: 8px solid #2e7d32;
    }
    
    .card-rejected {
        border-left: 8px solid #c62828;
    }
    
    .card-pending {
        border-left: 8px solid #f57c00;
    }

    /* ШАПКА КАРТОЧКИ */
    .planning-card-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 15px 20px;
        background: linear-gradient(135deg, #8b4513, #6b3100);
        color: white;
    }

    .planning-id {
        font-size: 16px;
        font-weight: bold;
        background: rgba(255,255,255,0.2);
        padding: 5px 12px;
        border-radius: 20px;
    }

    .status-badge {
        padding: 5px 15px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: bold;
    }

    .status-approved {
        background: #2e7d32;
        color: white;
    }

    .status-rejected {
        background: #c62828;
        color: white;
    }

    .status-pending {
        background: #f57c00;
        color: white;
    }

    /* ТЕЛО КАРТОЧКИ */
    .planning-card-body {
        padding: 18px;
        background: #faf5eb;
    }

    .planning-field {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        padding: 10px 0;
        border-bottom: 1px dashed #e0d0b0;
    }

    .planning-field:last-child {
        border-bottom: none;
    }

    .field-label {
        font-weight: bold;
        color: #8b4513;
        font-size: 13px;
        min-width: 120px;
    }

    .field-value {
        color: #555;
        font-size: 13px;
        text-align: right;
        max-width: 60%;
        word-break: break-word;
    }

    /* ФУТЕР КАРТОЧКИ */
    .planning-card-footer {
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
        padding: 15px;
        background: #f8f1e5;
        border-top: 1px solid #e0d0b0;
    }

    .btn-card-view {
        flex: 1;
        background: #5d4037;
        color: white;
        text-decoration: none;
        padding: 8px;
        text-align: center;
        border-radius: 8px;
        font-size: 13px;
        transition: all 0.3s;
    }

    .btn-card-view:hover {
        background: #3e2723;
        color: white;
        text-decoration: none;
        transform: scale(1.02);
    }

    .btn-card-approve {
        flex: 1;
        background: #2e7d32;
        color: white;
        text-decoration: none;
        padding: 8px;
        text-align: center;
        border-radius: 8px;
        font-size: 13px;
        transition: all 0.3s;
    }

    .btn-card-approve:hover {
        background: #1b5e20;
        color: white;
        text-decoration: none;
        transform: scale(1.02);
    }

    .btn-card-reject {
        flex: 1;
        background: #f57c00;
        color: white;
        text-decoration: none;
        padding: 8px;
        text-align: center;
        border-radius: 8px;
        font-size: 13px;
        transition: all 0.3s;
    }

    .btn-card-reject:hover {
        background: #e65100;
        color: white;
        text-decoration: none;
        transform: scale(1.02);
    }

    .btn-card-delete {
        flex: 1;
        background: #c62828;
        color: white;
        text-decoration: none;
        padding: 8px;
        text-align: center;
        border-radius: 8px;
        font-size: 13px;
        transition: all 0.3s;
    }

    .btn-card-delete:hover {
        background: #b71c1c;
        color: white;
        text-decoration: none;
        transform: scale(1.02);
    }

    /* АДАПТАЦИЯ ДЛЯ ПЛАНШЕТОВ */
    @media (max-width: 992px) {
        .planning-cards {
            grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
            gap: 20px;
        }
    }

    /* АДАПТАЦИЯ ДЛЯ ТЕЛЕФОНОВ */
    @media (max-width: 768px) {
        .chess-theme {
            padding: 15px;
        }
        
        .planning-cards {
            grid-template-columns: 1fr;
            gap: 20px;
        }
        
        .planning-card-header {
            padding: 12px 15px;
        }
        
        .planning-card-body {
            padding: 12px;
        }
        
        .planning-field {
            flex-direction: column;
            align-items: flex-start;
            gap: 5px;
        }
        
        .field-label {
            min-width: auto;
        }
        
        .field-value {
            text-align: left;
            max-width: 100%;
        }
        
        .planning-card-footer {
            flex-wrap: wrap;
        }
        
        .btn-card-view,
        .btn-card-approve,
        .btn-card-reject,
        .btn-card-delete {
            flex: 1 1 calc(50% - 8px);
            font-size: 12px;
            padding: 8px;
        }
    }

    @media (max-width: 480px) {
        .chess-theme {
            padding: 10px;
        }
        
        .chess-main-title {
            font-size: 20px;
        }
        
        .planning-id {
            font-size: 12px;
        }
        
        .status-badge {
            font-size: 10px;
            padding: 3px 10px;
        }
        
        .field-label, .field-value {
            font-size: 11px;
        }
        
        .btn-card-view,
        .btn-card-approve,
        .btn-card-reject,
        .btn-card-delete {
            flex: 1 1 100%;
            font-size: 11px;
        }
    }
</style>