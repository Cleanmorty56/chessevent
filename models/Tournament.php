<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "tournament".
 *
 * @property int $id
 * @property string $img
 * @property string $name
 * @property string $description
 * @property int $gamemode_id
 * @property string $location
 * @property int $quantity_rounds
 * @property string $status
 * @property int $level_id
 *
 * @property Gamemode $gamemode
 * @property Level $level
 * @property RegToTournament[] $regToTournaments
 */
class Tournament extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'tournament';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['img', 'name', 'description', 'gamemode_id', 'location', 'quantity_rounds', 'status', 'level_id'], 'required'],
            [['gamemode_id', 'quantity_rounds', 'level_id'], 'integer'],
            [['img'], 'file', 'skipOnEmpty' => true, 'extensions' => 'png, jpg, jpeg, gif'],
            [['name', 'location'], 'string', 'max' => 90],
            [['description'], 'string', 'max' => 255],
            [['gamemode_id'], 'exist', 'skipOnError' => true, 'targetClass' => Gamemode::class, 'targetAttribute' => ['gamemode_id' => 'id']],
            [['level_id'], 'exist', 'skipOnError' => true, 'targetClass' => Level::class, 'targetAttribute' => ['level_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'Идентификатор турнира',
            'img' => 'Главное изображение турнира',
            'name' => 'Название',
            'description' => 'Описание',
            'gamemode_id' => 'Игровой режим',
            'location' => 'Место проведения',
            'quantity_rounds' => 'Количество туров',
            'status' => 'Статус',
            'level_id' => 'Уровень турнира',
        ];
    }

    /**
     * Gets query for [[Gamemode]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getGamemode()
    {
        return $this->hasOne(Gamemode::class, ['id' => 'gamemode_id']);
    }

    /**
     * Gets query for [[Level]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getLevel()
    {
        return $this->hasOne(Level::class, ['id' => 'level_id']);
    }

    /**
     * Gets query for [[RegToTournaments]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getRegToTournaments()
    {
        return $this->hasMany(RegToTournament::class, ['tournament_id' => 'id']);
    }

    public function upload()
    {
        if ($this->validate()) {
            $this->img->saveAs('uploads/' . $this->img->baseName . '.' . $this->img->extension);
            return true;
        } else {
            return false;
        }
    }
    const STATUS_PLANNED = 'Запланирован';
    const STATUS_IN_PROGRESS = 'В процессе';
    const STATUS_COMPLETED = 'Завершен';

    public function isAvailableForRegistration()
    {
        return $this->status === self::STATUS_PLANNED;
    }

    /**
     * Gets query for [[TournamentMatches]].
     */
    public function getTournamentMatches()
    {
        return $this->hasMany(TournamentMatch::class, ['tournament_id' => 'id']);
    }

    /**
     * Проверяет, зарегистрирован ли пользователь на турнир
     */
    public function isUserRegistered($userId)
    {
        return RegToTournament::find()
            ->where(['tournament_id' => $this->id, 'user_id' => $userId])
            ->exists();
    }

    /**
     * Получает количество участников
     */
    public function getParticipantsCount()
    {
        return $this->getRegToTournaments()->count();
    }
}
