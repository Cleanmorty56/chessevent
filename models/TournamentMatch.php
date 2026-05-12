<?php

namespace app\models;

use Yii;

class TournamentMatch extends \yii\db\ActiveRecord
{
    const RESULT_PENDING = 'pending';
    const RESULT_WHITE_WIN = 'white_win';
    const RESULT_BLACK_WIN = 'black_win';
    const RESULT_DRAW = 'draw';

    const STATUS_PENDING = 'pending';
    const STATUS_PLAYED = 'played';

    public static function tableName()
    {
        return 'tournament_matches';
    }

    public function rules()
    {
        return [
            [['tournament_id', 'round', 'white_player_id', 'black_player_id'], 'required'],
            [['tournament_id', 'round', 'white_player_id', 'black_player_id', 'winner_id'], 'integer'],
            [['result', 'status'], 'string'],
            [['played_at', 'created_at'], 'safe'],
            [['tournament_id'], 'exist', 'skipOnError' => true, 'targetClass' => Tournament::class, 'targetAttribute' => ['tournament_id' => 'id']],
            [['white_player_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['white_player_id' => 'id']],
            [['black_player_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['black_player_id' => 'id']],
            [['winner_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['winner_id' => 'id']],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'tournament_id' => 'Турнир',
            'round' => 'Тур',
            'white_player_id' => 'Белые',
            'black_player_id' => 'Черные',
            'result' => 'Результат',
            'winner_id' => 'Победитель',
            'status' => 'Статус',
            'played_at' => 'Сыграно',
        ];
    }

    public function getTournament()
    {
        return $this->hasOne(Tournament::class, ['id' => 'tournament_id']);
    }

    public function getWhitePlayer()
    {
        return $this->hasOne(User::class, ['id' => 'white_player_id']);
    }

    public function getBlackPlayer()
    {
        return $this->hasOne(User::class, ['id' => 'black_player_id']);
    }

    public function getWinner()
    {
        return $this->hasOne(User::class, ['id' => 'winner_id']);
    }

    public function getResultLabel()
    {
        $labels = [
            self::RESULT_PENDING => 'Ожидает',
            self::RESULT_WHITE_WIN => 'Победа белых',
            self::RESULT_BLACK_WIN => 'Победа черных',
            self::RESULT_DRAW => 'Ничья',
        ];
        return $labels[$this->result] ?? $this->result;
    }

    public function isPlayed()
    {
        return $this->status === self::STATUS_PLAYED;
    }
}