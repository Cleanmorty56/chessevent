<?php

use yii\helpers\Html;
use yii\helpers\Url;
use app\models\TournamentMatch;
use app\models\TournamentBye;

$this->title = 'Управление: ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Турниры', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Управление';

// Считаем очки для каждого участника
$points = [];
foreach ($participants as $p) {
    $userId = $p->user_id;

    // Очки из партий
    $matches = TournamentMatch::find()
        ->where(['tournament_id' => $model->id])
        ->andWhere(['or', ['white_player_id' => $userId], ['black_player_id' => $userId]])
        ->andWhere(['status' => 'played'])
        ->all();

    $pts = 0;
    foreach ($matches as $m) {
        if ($m->winner_id == $userId) $pts += 1;
        elseif ($m->result == 'draw') $pts += 0.5;
    }

    // Очки за пропуски
    $byePts = TournamentBye::find()
        ->where(['tournament_id' => $model->id, 'user_id' => $userId])
        ->sum('points') ?? 0;

    $points[$userId] = $pts + $byePts;
}

// Сортируем участников по очкам
usort($participants, function($a, $b) use ($points) {
    $pa = $points[$a->user_id] ?? 0;
    $pb = $points[$b->user_id] ?? 0;
    if ($pa != $pb) return $pb - $pa;
    return ($b->user->elo ?? 1200) - ($a->user->elo ?? 1200);
});

$currentRound = $hasDraw ? TournamentMatch::find()->where(['tournament_id' => $model->id])->max('round') : 0;
?>

<div class="tournament-manage">
    <h1><?= Html::encode($this->title) ?></h1>

    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card">
                <div class="card-header">Статус</div>
                <div class="card-body">
                    <p><strong><?= $model->status ?></strong></p>
                    <p>Участников: <?= count($participants) ?></p>
                    <p>Тур: <?= $currentRound ?> / <?= $model->quantity_rounds ?></p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-header">Статистика</div>
                <div class="card-body">
                    <p>Партий: <?= $hasDraw ? array_sum(array_map('count', $rounds)) : 0 ?></p>
                    <p>Сыграно: <?= $hasDraw ? array_sum(array_map('count', $rounds)) - $pendingMatches : 0 ?></p>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">Действия</div>
                <div class="card-body">
                    <?php if (!$hasDraw && $model->status == 'Запланирован'): ?>
                        <?= Html::a('Провести жеребьевку (1 тур)', ['draw', 'id' => $model->id], [
                            'class' => 'btn btn-success',
                            'data' => ['confirm' => 'Провести жеребьевку первого тура?', 'method' => 'post'],
                        ]) ?>
                    <?php elseif ($model->status == 'В процессе'): ?>
                        <?php if ($currentRound < $model->quantity_rounds && $pendingMatches == 0): ?>
                            <?= Html::a('Провести жеребьевку (' . ($currentRound + 1) . ' тур)', ['draw', 'id' => $model->id], [
                                'class' => 'btn btn-success',
                                'data' => ['confirm' => 'Провести жеребьевку ' . ($currentRound + 1) . '-го тура?', 'method' => 'post'],
                            ]) ?>
                        <?php elseif ($pendingMatches > 0): ?>
                            <button class="btn btn-secondary" disabled>Дождитесь окончания тура</button>
                        <?php endif; ?>

                        <?= Html::a('Сбросить жеребьевку', ['reset-draw', 'id' => $model->id], [
                            'class' => 'btn btn-danger',
                            'data' => ['confirm' => 'Удалить все партии?', 'method' => 'post'],
                        ]) ?>
                    <?php endif; ?>

                    <?= Html::a('Назад', ['view', 'id' => $model->id], ['class' => 'btn btn-default']) ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Flash сообщения -->
    <?php if (Yii::$app->session->hasFlash('success')): ?>
        <div class="alert alert-success"><?= Yii::$app->session->getFlash('success') ?></div>
    <?php endif; ?>
    <?php if (Yii::$app->session->hasFlash('error')): ?>
        <div class="alert alert-danger"><?= Yii::$app->session->getFlash('error') ?></div>
    <?php endif; ?>

    <!-- Турнирная таблица -->
    <h3>Турнирная таблица</h3>
    <table class="table table-bordered">
        <thead>
        <tr>
            <th>Место</th>
            <th>Игрок</th>
            <th>ELO</th>
            <th>Очки</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($participants as $i => $p): ?>
            <tr>
                <td><?= $i + 1 ?></td>
                <td><?= Html::encode($p->user->username) ?></td>
                <td><?= $p->user->elo ?? 1200 ?></td>
                <td><strong><?= $points[$p->user_id] ?? 0 ?></strong></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>

    <!-- Партии -->
    <?php if ($hasDraw): ?>
        <h3>Партии</h3>
        <?php foreach ($rounds as $round => $matches): ?>
            <h4>Тур <?= $round ?></h4>
            <table class="table table-bordered">
                <thead>
                <tr>
                    <th>Белые</th>
                    <th>Черные</th>
                    <th>Результат</th>
                    <th>Действие</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($matches as $m): ?>
                    <tr>
                        <td><?= $m->whitePlayer->username ?> <?= $m->winner_id == $m->white_player_id ? '👑' : '' ?></td>
                        <td><?= $m->blackPlayer->username ?> <?= $m->winner_id == $m->black_player_id ? '👑' : '' ?></td>
                        <td>
                            <?php if ($m->isPlayed()): ?>
                                <?= $m->getResultLabel() ?>
                            <?php else: ?>
                                <span class="label label-warning">Ожидает</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php if (!$m->isPlayed()): ?>
                                <button class="btn btn-sm btn-primary" onclick="showResultModal(<?= $m->id ?>, '<?= $m->whitePlayer->username ?>', '<?= $m->blackPlayer->username ?>')">
                                    Ввести результат
                                </button>
                            <?php else: ?>
                                —
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        <?php endforeach; ?>
    <?php endif; ?>
</div>

<!-- Модальное окно -->
<div class="modal fade" id="resultModal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Результат партии</h4>
            </div>
            <form method="post" id="resultForm">
                <div class="modal-body">
                    <input type="hidden" name="<?= Yii::$app->request->csrfParam ?>" value="<?= Yii::$app->request->csrfToken ?>">
                    <input type="hidden" name="match_id" id="matchId">
                    <p><strong>Белые:</strong> <span id="whitePlayer"></span></p>
                    <p><strong>Черные:</strong> <span id="blackPlayer"></span></p>
                    <select name="result" class="form-control" required>
                        <option value="">Выберите результат</option>
                        <option value="white_win">Победа белых</option>
                        <option value="black_win">Победа черных</option>
                        <option value="draw">Ничья</option>
                    </select>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Отмена</button>
                    <button type="submit" class="btn btn-primary">Сохранить</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function showResultModal(id, white, black) {
        document.getElementById('matchId').value = id;
        document.getElementById('whitePlayer').textContent = white;
        document.getElementById('blackPlayer').textContent = black;
        document.getElementById('resultForm').action = '<?= Url::to(['update-match']) ?>?id=' + id;
        $('#resultModal').modal('show');
    }
</script>