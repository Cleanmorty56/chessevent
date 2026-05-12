<?php

namespace app\controllers;

use Yii;
use app\models\Tournament;
use app\models\TournamentMatch;
use app\models\TournamentBye;
use app\models\RegToTournament;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

class TournamentController extends Controller
{
    /**
     * Публичная страница жеребьевки турнира
     */
    public function actionDraw($id)
    {
        $model = Tournament::findOne($id);

        if (!$model) {
            throw new NotFoundHttpException('Турнир не найден.');
        }

        // Получаем участников
        $registrations = RegToTournament::find()
            ->where(['tournament_id' => $id])
            ->with('user')
            ->all();

        // Сортируем по ELO
        usort($registrations, function($a, $b) {
            $eloA = $a->user->elo ?? 1000;
            $eloB = $b->user->elo ?? 1000;
            return $eloB - $eloA;
        });

        // Получаем партии
        $matches = TournamentMatch::find()
            ->where(['tournament_id' => $id])
            ->with(['whitePlayer', 'blackPlayer'])
            ->orderBy(['round' => SORT_ASC, 'id' => SORT_ASC])
            ->all();

        // Группируем по турам
        $rounds = [];
        foreach ($matches as $match) {
            $rounds[$match->round][] = $match;
        }

        // Считаем очки
        $points = [];
        foreach ($registrations as $reg) {
            $userId = $reg->user_id;
            $pts = 0;

            // Очки из партий
            foreach ($matches as $m) {
                if ($m->status == 'played') {
                    if ($m->winner_id == $userId) {
                        $pts += 1;
                    } elseif ($m->result == 'draw' &&
                        ($m->white_player_id == $userId || $m->black_player_id == $userId)) {
                        $pts += 0.5;
                    }
                }
            }

            // Очки за пропуски
            $byePts = TournamentBye::find()
                ->where(['tournament_id' => $id, 'user_id' => $userId])
                ->sum('points') ?? 0;

            $points[$userId] = $pts + $byePts;
        }

        // Сортируем по очкам
        usort($registrations, function($a, $b) use ($points) {
            $pa = $points[$a->user_id] ?? 0;
            $pb = $points[$b->user_id] ?? 0;
            if ($pa != $pb) return $pb - $pa;
            return ($b->user->elo ?? 1000) - ($a->user->elo ?? 1000);
        });

        return $this->render('draw', [
            'model' => $model,
            'registrations' => $registrations,
            'rounds' => $rounds,
            'points' => $points,
        ]);
    }
}