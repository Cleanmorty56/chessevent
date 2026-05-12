<?php

use yii\helpers\Html;
use app\models\Tournament;
use app\models\TournamentMatch;
use yii\helpers\Url;

$this->title = 'Жеребьевка: ' . $model->name;
?>

<div class="tournament-draw" id="printable-area">
    <div class="no-print text-center mb-4">
        <button onclick="window.print()" class="btn btn-chess btn-lg">
            <i class="fas fa-print"></i> Распечатать жеребьевку
        </button>
        <a href="<?= Url::to(['site/tournaments']) ?>" class="btn btn-secondary btn-lg ml-2">
            <i class="fas fa-arrow-left"></i> Назад
        </a>
    </div>

    <div class="text-center mb-4">
        <h1><?= Html::encode($model->name) ?></h1>
        <p class="lead">
            <?= Html::encode($model->location) ?> |
            Статус: <strong><?= $model->status ?></strong> |
            Туров: <?= $model->quantity_rounds ?>
        </p>
        <p class="print-date">Дата печати: <?= date('d.m.Y H:i') ?></p>
    </div>

    <!-- Турнирная таблица -->
    <h2>Турнирная таблица</h2>
    <table class="table table-bordered table-striped">
        <thead>
        <tr>
            <th>Место</th>
            <th>Игрок</th>
            <th>ELO</th>
            <th>Очки</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($registrations as $i => $reg): ?>
            <tr>
                <td><strong><?= $i + 1 ?></strong></td>
                <td><?= Html::encode($reg->user->username) ?></td>
                <td><?= $reg->user->elo ?? 1000 ?></td>
                <td><strong><?= $points[$reg->user_id] ?? 0 ?></strong></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>

    <!-- Партии по турам -->
    <?php if (!empty($rounds)): ?>
        <h2 class="mt-4">Партии</h2>
        <?php foreach ($rounds as $round => $matches): ?>
            <div class="card mb-3 round-card">
                <div class="card-header">
                    <h4>Тур <?= $round ?></h4>
                </div>
                <div class="card-body">
                    <table class="table table-bordered mb-0">
                        <thead>
                        <tr>
                            <th width="5%">#</th>
                            <th width="38%">Белые</th>
                            <th width="38%">Черные</th>
                            <th width="19%">Результат</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($matches as $i => $match): ?>
                            <tr>
                                <td><?= $i + 1 ?></td>
                                <td>
                                    <?= $match->winner_id === $match->white_player_id ? '👑 ' : '' ?>
                                    <?= Html::encode($match->whitePlayer->username) ?>
                                </td>
                                <td>
                                    <?= $match->winner_id === $match->black_player_id ? '👑 ' : '' ?>
                                    <?= Html::encode($match->blackPlayer->username) ?>
                                </td>
                                <td>
                                    <?php if ($match->isPlayed()): ?>
                                        <span class="badge badge-<?=
                                        $match->result == 'white_win' ? 'light' :
                                            ($match->result == 'black_win' ? 'dark' : 'secondary')
                                        ?>">
                                                <?= $match->getResultLabel() ?>
                                            </span>
                                    <?php else: ?>
                                        <span class="badge badge-warning">Ожидает</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <!-- Разрыв страницы при печати после каждого тура -->
            <div class="page-break"></div>
        <?php endforeach; ?>
    <?php else: ?>
        <div class="alert alert-info mt-4">
            Жеребьевка еще не проводилась.
        </div>
    <?php endif; ?>
</div>

<style>
    .tournament-draw {
        max-width: 900px;
        margin: 0 auto;
        padding: 20px;
    }

    .badge-light {
        background: #e0e0e0;
        color: #333;
        border: 1px solid #999;
    }

    .badge-dark {
        background: #333;
        color: #fff;
    }

    .btn-chess {
        background: linear-gradient(to bottom, #8b4513, #6b3100);
        color: #f0e6d2 !important;
        border: 2px solid #5d4037;
        border-radius: 8px;
        text-align: center;
        text-decoration: none;
        cursor: pointer;
        padding: 10px 20px;
    }

    .btn-chess:hover {
        background: linear-gradient(to bottom, #a0522d, #8b4513);
    }

    .btn-lg {
        padding: 12px 30px;
        font-size: 18px;
    }

    .ml-2 {
        margin-left: 10px;
    }

    .print-date {
        color: #999;
        font-size: 14px;
    }

    /* Стили для печати */
    @media print {
        .no-print {
            display: none !important;
        }

        body {
            background: white !important;
        }

        .tournament-draw {
            max-width: 100%;
            padding: 0;
        }

        .card {
            border: 1px solid #000 !important;
            page-break-inside: avoid;
        }

        .card-header {
            background: #f0f0f0 !important;
            color: #000 !important;
            border-bottom: 2px solid #000 !important;
        }

        .table {
            border-collapse: collapse;
        }

        .table th, .table td {
            border: 1px solid #000 !important;
            color: #000 !important;
        }

        .badge {
            border: 1px solid #000 !important;
        }

        .badge-light {
            background: #f0f0f0 !important;
            color: #000 !important;
        }

        .badge-dark {
            background: #333 !important;
            color: #fff !important;
        }

        .badge-warning {
            background: #f0f0f0 !important;
            color: #000 !important;
            border: 1px solid #000 !important;
        }

        h1, h2, h3, h4 {
            color: #000 !important;
        }

        .page-break {
            page-break-after: always;
        }

        @page {
            margin: 1cm;
            size: A4;
        }
    }
</style>