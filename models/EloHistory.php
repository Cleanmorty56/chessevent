<?php

namespace app\models;

use Yii;

class EloHistory extends \yii\db\ActiveRecord
{
    public static function tableName()
    {
        return 'elo_history';
    }

    public function rules()
    {
        return [
            [['user_id', 'elo_before', 'elo_after', 'change', 'reason'], 'required'],
            [['user_id', 'game_id', 'elo_before', 'elo_after', 'change'], 'integer'],
            [['reason'], 'string'],
            [['created_at'], 'safe'],
        ];
    }

    public function getUser()
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }

    public function getGame()
    {
        return $this->hasOne(Game::class, ['id' => 'game_id']);
    }

    public static function logChange($userId, $gameId, $eloBefore, $eloAfter, $reason = 'game')
    {
        $history = new self();
        $history->user_id = $userId;
        $history->game_id = $gameId;
        $history->elo_before = $eloBefore;
        $history->elo_after = $eloAfter;
        $history->change = $eloAfter - $eloBefore;
        $history->reason = $reason;
        return $history->save();
    }
}