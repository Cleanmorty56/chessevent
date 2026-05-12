<?php

namespace app\models;

use Yii;

class TournamentBye extends \yii\db\ActiveRecord
{
    public static function tableName()
    {
        return 'tournament_byes';
    }

    public function rules()
    {
        return [
            [['tournament_id', 'user_id', 'round'], 'required'],
            [['tournament_id', 'user_id', 'round'], 'integer'],
            [['points'], 'number'],
            [['created_at'], 'safe'],
            [['tournament_id'], 'exist', 'skipOnError' => true, 'targetClass' => Tournament::class, 'targetAttribute' => ['tournament_id' => 'id']],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['user_id' => 'id']],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'tournament_id' => 'Турнир',
            'user_id' => 'Участник',
            'round' => 'Тур',
            'points' => 'Очки',
            'created_at' => 'Дата',
        ];
    }

    public function getTournament()
    {
        return $this->hasOne(Tournament::class, ['id' => 'tournament_id']);
    }

    public function getUser()
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }
}