<?php
namespace app\models;

use Yii;
use yii\db\ActiveRecord;

/**
 * @property int $id
 * @property int $game_id
 * @property int $user_id
 * @property int $move_number
 * @property string $move_san
 * @property string $move_fen
 * @property string $created_at
 *
 * @property Game $game
 * @property User $user
 */
class Move extends ActiveRecord
{
    public static function tableName()
    {
        return 'move';
    }

    public function rules()
    {
        return [
            [['game_id', 'user_id', 'move_number', 'move_san', 'move_fen'], 'required'],
            [['game_id', 'user_id', 'move_number'], 'integer'],
            [['created_at'], 'safe'],
            [['move_san'], 'string', 'max' => 10],
            [['move_fen'], 'string', 'max' => 100],
            [['game_id'], 'exist', 'skipOnError' => true, 'targetClass' => Game::class, 'targetAttribute' => ['game_id' => 'id']],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['user_id' => 'id']],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'game_id' => 'Игра',
            'user_id' => 'Игрок',
            'move_number' => 'Номер хода',
            'move_san' => 'Ход (SAN)',
            'move_fen' => 'Позиция (FEN)',
            'created_at' => 'Время хода',
        ];
    }

    public function getGame()
    {
        return $this->hasOne(Game::class, ['id' => 'game_id']);
    }

    public function getUser()
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }

    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            if ($insert) {
                $this->created_at = date('Y-m-d H:i:s');
            }
            return true;
        }
        return false;
    }
}