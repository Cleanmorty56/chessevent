<?php

namespace app\controllers;

use app\models\Game;
use app\models\Move;
use app\models\PasswordChangeForm;
use app\models\ProfileChangeForm;
use app\models\User;
use Yii;
use yii\web\Controller;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;

class ProfileController extends Controller
{
    public function actionProfile()
    {
        $user = Yii::$app->user->identity;
        $model = User::findOne($user->getId());
        $tournaments = $model->tournaments;

        return $this->render('profile', [
            "model" => $model,
            "tournaments" => $tournaments,
        ]);
    }

    public function actionUpdate()
    {
        $user = Yii::$app->user->identity;
        $model = new ProfileChangeForm();

        // Заполняем форму текущими данными
        $model->email = $user->email;
        $model->first_name = $user->first_name;
        $model->last_name = $user->last_name;

        if ($model->load(Yii::$app->request->post()) && $model->update($user)) {
            Yii::$app->session->setFlash('success', 'Профиль успешно обновлён!');
            return $this->redirect(['profile/profile']);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    public function actionPassword()
    {
        $user = User::findOne(Yii::$app->user->identity->getId());
        $model = new PasswordChangeForm();

        if ($model->load(Yii::$app->request->post())) {
            if ($model->validate() && $user->validatePassword($model->oldPassword)) {
                if ($model->changePassword()) {
                    Yii::$app->session->setFlash('success', 'Пароль успешно изменен');
                    return $this->redirect(['profile/profile']);
                }
            }
        }

        return $this->render('password', ['model' => $model]);
    }

    /**
     * Скачивание партии в формате PGN
     */
    public function actionDownloadPgn($gameId)
    {
        $game = Game::findOne($gameId);

        if (!$game) {
            throw new NotFoundHttpException('Партия не найдена.');
        }

        if ($game->white_user_id != Yii::$app->user->id && $game->black_user_id != Yii::$app->user->id) {
            throw new ForbiddenHttpException('Доступ запрещен.');
        }

        $moves = Move::find()
            ->where(['game_id' => $gameId])
            ->orderBy(['move_number' => SORT_ASC])
            ->all();

        $pgn = '';
        $pgn .= '[Event "Online Chess"]' . "\n";
        $pgn .= '[Site "Chess Site"]' . "\n";
        $pgn .= '[Date "' . date('Y.m.d', strtotime($game->started_at ?? 'now')) . '"]' . "\n";
        $pgn .= '[White "' . ($game->whiteUser->username ?? 'Unknown') . '"]' . "\n";
        $pgn .= '[Black "' . ($game->blackUser->username ?? 'Unknown') . '"]' . "\n";

        if ($game->status == 'draw') {
            $pgn .= '[Result "1/2-1/2"]' . "\n";
        } elseif ($game->winner_id == $game->white_user_id) {
            $pgn .= '[Result "1-0"]' . "\n";
        } elseif ($game->winner_id == $game->black_user_id) {
            $pgn .= '[Result "0-1"]' . "\n";
        } else {
            $pgn .= '[Result "*"]' . "\n";
        }

        $pgn .= "\n";

        $moveNumber = 1;
        $moveText = '';
        foreach ($moves as $i => $move) {
            if ($i % 2 == 0) {
                $moveText .= $moveNumber . '. ' . $move->move_san . ' ';
            } else {
                $moveText .= $move->move_san . ' ';
                $moveNumber++;
            }
        }

        $pgn .= wordwrap($moveText, 80) . "\n\n";

        if ($game->status == 'draw') {
            $pgn .= '1/2-1/2';
        } elseif ($game->winner_id == $game->white_user_id) {
            $pgn .= '1-0';
        } elseif ($game->winner_id == $game->black_user_id) {
            $pgn .= '0-1';
        } else {
            $pgn .= '*';
        }

        $filename = 'game_' . $gameId . '_' . date('Y-m-d') . '.pgn';

        Yii::$app->response->sendContentAsFile($pgn, $filename, [
            'mimeType' => 'application/x-chess-pgn',
            'inline' => false,
        ]);

        return Yii::$app->response->send();
    }
}