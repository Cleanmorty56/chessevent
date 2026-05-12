<?php

use app\models\RegToTournament;
use app\models\Game;
use app\models\Move;
use app\models\EloHistory;
use yii\bootstrap5\Html;
use yii\helpers\Url;
use yii\widgets\DetailView;
use yii\widgets\LinkPager;
$this->registerCssFile("@web/css/profile.css");

?>
<div class="profile-page">
    <!-- Шапка профиля -->
    <div class="profile-header">
        <div class="profile-avatar">
            <?= Html::img('@web/img/free-icon-user-2550260.png', ['alt' => 'avatar']) ?>
            <?php if (!empty($model->first_name) || !empty($model->last_name)): ?>
                <div class="avatar-name">
                    <?= Html::encode($model->first_name . ' ' . $model->last_name) ?>
                </div>
            <?php endif; ?>
        </div>
        <div class="profile-info-header">
            <h1><?= Html::encode($model->username) ?></h1>
            <?php if (!empty($model->first_name) || !empty($model->last_name)): ?>
                <div class="profile-fullname">
                    <i class="fas fa-user-circle"></i> <?= Html::encode($model->first_name . ' ' . $model->last_name) ?>
                </div>
            <?php endif; ?>
            <div class="profile-badges">
                <span class="badge-elo">Рейтинг ELO: <?= $model->elo ?></span>
                <span class="badge-level">
                    <?php
                    if ($model->elo >= 2000) echo 'Гроссмейстер';
                    elseif ($model->elo >= 1800) echo 'Мастер';
                    elseif ($model->elo >= 1600) echo 'Эксперт';
                    elseif ($model->elo >= 1400) echo 'Продвинутый';
                    elseif ($model->elo >= 1200) echo 'Средний';
                    elseif ($model->elo >= 1000) echo 'Начинающий';
                    else echo 'Новичок';
                    ?>
                </span>
            </div>
            <div class="profile-actions-header">
                <a href="<?= Url::to(['profile/update']) ?>" class="btn-action">
                    <i class="fas fa-user-edit"></i> Редактировать профиль
                </a>
                <a href="<?= Url::to(['profile/password']) ?>" class="btn-action">
                    <i class="fas fa-key"></i> Сменить пароль
                </a>
            </div>
        </div>
    </div>

    <!-- Сетка с карточками -->
    <div class="profile-grid">
        <!-- Карточка информации -->
        <div class="info-card">
            <h3><i class="fas fa-user-circle"></i> О игроке</h3>
            <div class="info-list">
                <div class="info-row">
                    <span class="info-label"><i class="fas fa-user"></i> Логин:</span>
                    <span class="info-value"><?= Html::encode($model->username) ?></span>
                </div>
                <div class="info-row">
                    <span class="info-label"><i class="fas fa-user-tag"></i> Имя:</span>
                    <span class="info-value"><?= Html::encode($model->first_name ?: '<span class="not-specified">не указано</span>') ?></span>
                </div>
                <div class="info-row">
                    <span class="info-label"><i class="fas fa-user-tag"></i> Фамилия:</span>
                    <span class="info-value"><?= Html::encode($model->last_name ?: '<span class="not-specified">не указано</span>') ?></span>
                </div>
                <div class="info-row">
                    <span class="info-label"><i class="fas fa-envelope"></i> Email:</span>
                    <span class="info-value"><?= Html::encode($model->email) ?></span>
                </div>
                <div class="info-row">
                    <span class="info-label"><i class="fas fa-map-marker-alt"></i> Регион:</span>
                    <span class="info-value"><?= Html::encode($model->region->name ?? '—') ?></span>
                </div>
                <div class="info-row">
                    <span class="info-label"><i class="fas fa-calendar-alt"></i> Регистрация:</span>
                    <span class="info-value"><?= Yii::$app->formatter->asDatetime($model->created_at, 'php:d.m.Y') ?></span>
                </div>
                <div class="info-row">
                    <span class="info-label"><i class="fas fa-chess-queen"></i> Рейтинг ELO:</span>
                    <span class="info-value elo-value"><?= $model->elo ?></span>
                </div>
            </div>
        </div>

        <!-- Карточка статистики -->
        <div class="stats-card">
            <h3><i class="fas fa-chart-simple"></i> Статистика</h3>
            <?php
            $wins = Game::find()->where(['winner_id' => $model->id])->count();
            $losses = Game::find()
                ->where([
                    'or',
                    ['white_user_id' => $model->id],
                    ['black_user_id' => $model->id]
                ])
                ->andWhere(['not', ['winner_id' => null]])
                ->andWhere(['not', ['winner_id' => $model->id]])
                ->count();
            $draws = Game::find()
                ->where([
                    'or',
                    ['white_user_id' => $model->id],
                    ['black_user_id' => $model->id]
                ])
                ->andWhere(['status' => 'draw'])
                ->count();
            $totalGames = $wins + $losses + $draws;
            $winRate = $totalGames > 0 ? round(($wins / $totalGames) * 100, 1) : 0;
            ?>
            <div class="stats-row">
                <div class="stat-item">
                    <div class="stat-number"><?= $totalGames ?></div>
                    <div class="stat-label">Всего игр</div>
                </div>
                <div class="stat-item stat-wins">
                    <div class="stat-number"><?= $wins ?></div>
                    <div class="stat-label">Победы</div>
                </div>
                <div class="stat-item stat-losses">
                    <div class="stat-number"><?= $losses ?></div>
                    <div class="stat-label">Поражения</div>
                </div>
                <div class="stat-item stat-draws">
                    <div class="stat-number"><?= $draws ?></div>
                    <div class="stat-label">Ничьи</div>
                </div>
                <div class="stat-item stat-rate">
                    <div class="stat-number"><?= $winRate ?>%</div>
                    <div class="stat-label">% побед</div>
                </div>
            </div>
        </div>

        <!-- История ELO -->
        <?php
        $eloHistory = EloHistory::find()
            ->where(['user_id' => $model->id])
            ->orderBy(['created_at' => SORT_DESC])
            ->limit(10)
            ->all();
        ?>
        <?php if (!empty($eloHistory)): ?>
            <div class="elo-card">
                <h3><i class="fas fa-chart-line"></i> История рейтинга</h3>
                <div class="elo-timeline">
                    <?php foreach ($eloHistory as $h): ?>
                        <div class="elo-point">
                            <div class="elo-date"><?= Yii::$app->formatter->asDate($h->created_at, 'php:d.m.Y') ?></div>
                            <div class="elo-change <?= $h->change > 0 ? 'up' : ($h->change < 0 ? 'down' : '') ?>">
                                <?= $h->change > 0 ? '+' : '' ?><?= $h->change ?>
                            </div>
                            <div class="elo-values">
                                <?= $h->elo_before ?> → <?= $h->elo_after ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endif; ?>

        <!-- История игр (горизонтальная прокрутка) -->
        <div class="games-card">
            <h3><i class="fas fa-history"></i> История игр</h3>
            <?php
            $page = Yii::$app->request->get('page', 1);
            $perPage = 15;
            $query = Game::find()
                ->where([
                    'or',
                    ['white_user_id' => $model->id],
                    ['black_user_id' => $model->id]
                ])
                ->andWhere(['not', ['status' => 'pending']])
                ->andWhere(['not', ['status' => 'active']])
                ->with(['whiteUser', 'blackUser'])
                ->orderBy(['finished_at' => SORT_DESC]);
            $totalCount = $query->count();
            $games = $query->offset(($page - 1) * $perPage)->limit($perPage)->all();
            $totalPages = ceil($totalCount / $perPage);
            ?>

            <?php if (!empty($games)): ?>
                <div class="games-scroll">
                    <table class="games-table">
                        <thead>
                        <tr>
                            <th>Дата</th>
                            <th>Соперник</th>
                            <th>Цвет</th>
                            <th>Результат</th>
                            <th></th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($games as $game):
                            $isWhite = $game->white_user_id == $model->id;
                            $opponent = $isWhite ? $game->blackUser : $game->whiteUser;
                            $myColor = $isWhite ? 'Белые' : 'Черные';

                            if ($game->winner_id == $model->id) {
                                $result = 'Победа';
                                $resultClass = 'result-win';
                            } elseif ($game->status == 'draw') {
                                $result = 'Ничья';
                                $resultClass = 'result-draw';
                            } elseif ($game->winner_id !== null && $game->winner_id != $model->id) {
                                $result = 'Поражение';
                                $resultClass = 'result-loss';
                            } else {
                                $result = '—';
                                $resultClass = '';
                            }
                            ?>
                            <tr>
                                <td class="game-date"><?= Yii::$app->formatter->asDate($game->finished_at ?? $game->started_at, 'php:d.m.Y') ?></td>
                                <td class="game-opponent"><?= Html::encode($opponent->username ?? 'Неизвестно') ?></td>
                                <td class="game-color <?= $isWhite ? 'white' : 'black' ?>"><?= $myColor ?></td>
                                <td class="game-result <?= $resultClass ?>"><?= $result ?></td>
                                <td><?= Html::a('PGN', ['profile/download-pgn', 'gameId' => $game->id], ['class' => 'btn-pgn', 'target' => '_blank']) ?></td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>

                <?php if ($totalPages > 1): ?>
                    <div class="pagination-bar">
                        <?php if ($page > 1): ?>
                            <a href="?page=<?= $page - 1 ?>" class="page-link">&laquo;</a>
                        <?php endif; ?>
                        <?php for ($i = 1; $i <= $totalPages && $i <= 5; $i++): ?>
                            <a href="?page=<?= $i ?>" class="page-link <?= $i == $page ? 'active' : '' ?>"><?= $i ?></a>
                        <?php endfor; ?>
                        <?php if ($page < $totalPages): ?>
                            <a href="?page=<?= $page + 1 ?>" class="page-link">&raquo;</a>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
            <?php else: ?>
                <div class="empty-message">Нет сыгранных партий</div>
            <?php endif; ?>
        </div>

        <!-- Турниры -->
        <div class="tournaments-card">
            <h3><i class="fas fa-trophy"></i> Мои турниры</h3>
            <?php if (!empty($tournaments)): ?>
                <div class="tournaments-grid">
                    <?php foreach ($tournaments as $t): ?>
                        <?php
                        $registration = RegToTournament::find()
                            ->where(['user_id' => $model->id, 'tournament_id' => $t->id])
                            ->one();
                        ?>
                        <div class="tournament-item">
                            <h4><?= Html::encode($t->name) ?></h4>
                            <p class="tournament-desc"><?= Html::encode($t->description) ?></p>
                            <span class="tournament-status status-<?= strtolower($t->status) ?>">
                                <?= Html::encode($t->status) ?>
                            </span>
                            <div class="tournament-reg-date">
                                <i class="fas fa-calendar-plus"></i>
                                <?= $registration ? Yii::$app->formatter->asDate($registration->registration_date, 'php:d.m.Y') : '-' ?>
                            </div>
                            <?= Html::a('Жеребьевка', ['/tournament/draw', 'id' => $t->id], ['class' => 'btn-tournament', 'target' => '_blank']) ?>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <div class="empty-message">Вы не зарегистрированы ни на один турнир</div>
            <?php endif; ?>
        </div>
    </div>
</div>
