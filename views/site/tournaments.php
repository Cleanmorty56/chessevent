<?php
$this->registerCssFile("@web/css/urovni.css");
$this->registerCssFile("@web/css/tour.css");

use yii\bootstrap5\Html;
use yii\helpers\Url;
?>

<div class="tournaments-page">
    <div class="level-titleh">
        <h1>Выберите уровень турнира:</h1>
    </div>

    <div class="levels-container">
        <a href="<?= Url::to(['site/tournaments', 'id' => 'all']) ?>"
           class="level-filter <?= ($selectedLevelId === null || $selectedLevelId === 'all') ? 'active' : '' ?>">
            <h5>Все турниры</h5>
        </a>

        <?php if(!empty($levels)): ?>
            <?php foreach ($levels as $level): ?>
                <a href="<?= Url::to(['site/tournaments', 'id' => $level->id]) ?>"
                   class="level-filter <?= ($selectedLevelId == $level->id) ? 'active' : '' ?>">
                    <?php if (!empty($level->img)): ?>
                    <?php else: ?>
                        <div class="level-icon">
                            <span>⭐</span>
                        </div>
                    <?php endif; ?>
                    <h5><?= Html::encode($level->name) ?></h5>
                </a>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
    <div class="tournaments-section">
        <?php if($selectedLevel): ?>
            <h2 class="selected-level-title">
                Турниры уровня: <?= Html::encode($selectedLevel->name) ?>
            </h2>
        <?php elseif($selectedLevelId === 'all' || $selectedLevelId === null): ?>
            <h2 class="selected-level-title">Все турниры</h2>
        <?php endif; ?>

        <?php if(!empty($tournaments)): ?>
            <div class="tournament-list">
                <?php foreach ($tournaments as $t): ?>
                    <div class="tournament-card">
                        <div class="tournament-header">
                            <?php if (!empty($t->img)): ?>
                                <?= Html::img(
                                    '@web/uploads/' . Html::encode($t->img),
                                    ['alt' => Html::encode($t->name)]
                                ) ?>
                            <?php endif; ?>
                            <div class="tournament-level-badge">
                                Уровень: <?= Html::encode($t->level->name ?? 'Не указан') ?>
                            </div>
                        </div>

                        <div class="tournament-name"><?= Html::encode($t->name) ?></div>
                        <div class="tournament-desc"><?= Html::encode($t->description) ?></div>

                        <div class="tournament-meta">
                            <div class="meta-item">
                                <span class="meta-label">Режим:</span>
                                <span class="meta-value"><?= Html::encode($t->gamemode->name ?? 'Не указан') ?></span>
                            </div>
                            <div class="meta-item">
                                <span class="meta-label">Локация:</span>
                                <span class="meta-value"><?= Html::encode($t->location) ?></span>
                            </div>
                            <div class="meta-item">
                                <span class="meta-label">Раундов:</span>
                                <span class="meta-value"><?= Html::encode($t->quantity_rounds) ?></span>
                            </div>
                            <div class="meta-item">
                                <span class="meta-label">Статус:</span>
                                <span class="meta-value status-<?= Html::encode(strtolower($t->status)) ?>">
                                    <?= Html::encode($t->status) ?>
                                </span>
                            </div>
                        </div>

                        <?php if (!Yii::$app->user->isGuest): ?>
                            <div class="tournament-actions">
                                <?php if ($t->isAvailableForRegistration()): ?>
                                    <?php if (in_array($t->id, $userTournamentIds)): ?>
                                        <span class="btn btn-success btn-block">Вы зарегистрированы</span>
                                    <?php else: ?>
                                        <?= Html::a('Записаться', ['site/register-for-tournament', 'tournamentId' => $t->id], [
                                            'class' => 'btn btn-chess',
                                            'data' => [
                                                'confirm' => 'Вы уверены, что хотите записаться на этот турнир?',
                                                'method' => 'post',
                                            ],
                                        ]) ?>
                                    <?php endif; ?>
                                <?php else: ?>
                                    <span class="btn btn-secondary btn-block">Регистрация закрыта</span>
                                <?php endif; ?>

                                <!-- КНОПКА ЖЕРЕБЬЕВКИ -->
                                <?= Html::a('📋 Жеребьевка', ['/tournament/draw', 'id' => $t->id], [
                                    'class' => 'btn btn-info btn-chess mt-2',
                                    'target' => '_blank',
                                ]) ?>
                            </div>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div class="no-tournaments">
                <h5>
                    <?php if($selectedLevel): ?>
                        На уровне "<?= Html::encode($selectedLevel->name) ?>" пока нет турниров
                    <?php else: ?>
                        Турниры не найдены
                    <?php endif; ?>
                </h5>
            </div>
        <?php endif; ?>
    </div>
</div>
<style>
    .tournament-header img {
        width: 250px;
        height: 150px;
        object-fit: cover;
        border-radius: 8px;
        border: 1px solid #ddd;
        display: block;
        margin: 0 auto;
    }

    .btn-chess {
        display: block;
        width: 100%;
        background: linear-gradient(to bottom, #8b4513, #6b3100);
        color: #f0e6d2 !important;
        border: 2px solid #5d4037;
        border-radius: 8px;
        text-align: center;
        text-decoration: none;
        cursor: pointer;
        padding: 10px;
        margin-bottom: 5px;
    }

    .btn-info {
        display: block;
        width: 100%;
        text-align: center;
        padding: 10px;
    }

    .btn-success, .btn-secondary {
        display: block;
        width: 100%;
        padding: 10px;
    }

    .btn-block {
        display: block;
        width: 100%;
    }

    .mt-2 {
        margin-top: 8px;
    }
</style>