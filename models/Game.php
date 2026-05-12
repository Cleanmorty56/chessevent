<?php
namespace app\models;

use Yii;
use yii\db\ActiveRecord;

/**
 * @property int $id
 * @property int $white_user_id
 * @property int $black_user_id
 * @property int|null $tournament_id
 * @property string $status
 * @property string $current_fen
 * @property string|null $started_at
 * @property string|null $finished_at
 * @property int|null $winner_id
 *
 * @property User $whiteUser
 * @property User $blackUser
 * @property User $winner
 * @property Move[] $moves
 */
class Game extends ActiveRecord
{
    const STATUS_PENDING = 'pending';
    const STATUS_ACTIVE = 'active';
    const STATUS_FINISHED = 'finished';
    const STATUS_DRAW = 'draw';
    const STATUS_WHITE_WIN = 'white_win';
    const STATUS_BLACK_WIN = 'black_win';

    public static function tableName()
    {
        return 'game';
    }

    public function rules()
    {
        return [
            [['white_user_id'], 'required'],
            [['white_user_id', 'black_user_id', 'tournament_id', 'winner_id'], 'integer'],
            [['status'], 'string'],
            [['started_at', 'finished_at'], 'safe'],
            [['current_fen'], 'string', 'max' => 100],
            [['white_user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['white_user_id' => 'id']],
            [['black_user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['black_user_id' => 'id']],
            [['winner_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['winner_id' => 'id']],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'white_user_id' => 'Белые',
            'black_user_id' => 'Черные',
            'tournament_id' => 'Турнир',
            'status' => 'Статус',
            'current_fen' => 'Текущая позиция',
            'started_at' => 'Начало игры',
            'finished_at' => 'Окончание игры',
            'winner_id' => 'Победитель',
        ];
    }

    public function getWhiteUser()
    {
        return $this->hasOne(User::class, ['id' => 'white_user_id']);
    }

    public function getBlackUser()
    {
        return $this->hasOne(User::class, ['id' => 'black_user_id']);
    }

    public function getWinner()
    {
        return $this->hasOne(User::class, ['id' => 'winner_id']);
    }

    public function getMoves()
    {
        return $this->hasMany(Move::class, ['game_id' => 'id'])->orderBy(['move_number' => SORT_ASC]);
    }

    public function startGame()
    {
        $this->status = self::STATUS_ACTIVE;
        $this->started_at = date('Y-m-d H:i:s');
        $this->current_fen = 'rnbqkbnr/pppppppp/8/8/8/8/PPPPPPPP/RNBQKBNR w KQkq - 0 1'; // Начальная позиция
        return $this->save();
    }

    public function endGame($result, $winnerId = null)
    {
        $this->status = $result;
        $this->winner_id = $winnerId;
        $this->finished_at = date('Y-m-d H:i:s');
        return $this->save();
    }
}