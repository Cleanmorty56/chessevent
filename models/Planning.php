<?php

namespace app\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\db\Expression;

/**
 * This is the model class for table "planning".
 *
 * @property int $id
 * @property string $content
 * @property string|null $organizer
 * @property int $user_id
 * @property int $gamemode_id
 * @property string $imageFile
 * @property int $quantity_rounds
 * @property string|null $created_at
 * @property string|null $updated_at
 * @property int|null $status
 *
 * @property Gamemode $gamemode
 * @property User $user
 */
class Planning extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'planning';
    }

    /**
     * {@inheritdoc}
     */

    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::class,
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['created_at', 'updated_at'],
                    ActiveRecord::EVENT_BEFORE_UPDATE => ['updated_at'],
                ],
                // if you're using datetime instead of UNIX timestamp:
               'value' => new Expression('NOW()'),
            ],
        ];
    }

    public function rules()
    {
        return [
            ['user_id','default', 'value'=> Yii::$app->user->getId()],
            [['content', 'gamemode_id', 'imageFile', 'quantity_rounds'], 'required'],
            [['user_id', 'gamemode_id', 'quantity_rounds', 'status'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['content'], 'string', 'max' => 255],
            [['imageFile'], 'file', 'skipOnEmpty' => false, 'extensions' => 'png, jpg'],
            [['organizer'], 'string', 'max' => 85],
            [['gamemode_id'], 'exist', 'skipOnError' => true, 'targetClass' => Gamemode::class, 'targetAttribute' => ['gamemode_id' => 'id']],
            ['status', 'default', 'value' => self::STATUS_PENDING],
        ];
    }



    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'content' => 'Поле для заявки',
            'organizer' => 'Для организации',
            'user_id' => 'User ID',
            'gamemode_id' => 'Игровой режим',
            'imageFile' => 'Главное фото',
            'quantity_rounds' => 'Количество туров',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'status' => 'Статус',
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
     * Gets query for [[User]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }

    public function upload()
    {
        if ($this->validate()) {
            $this->imageFile->saveAs('uploads/' . $this->imageFile->baseName . '.' . $this->imageFile->extension);
            return true;
        } else {
            return false;
        }
    }

    // Добавьте в начало класса Planning
    const STATUS_PENDING = 0;
    const STATUS_REJECTED = 1;
    const STATUS_APPROVED = 2;

// Добавьте этот метод в класс Planning
    public static function getStatuses()
    {
        return [
            self::STATUS_PENDING => 'На рассмотрении',
            self::STATUS_REJECTED => 'Отклонено',
            self::STATUS_APPROVED => 'Одобрено',
        ];
    }

    public function approve()
    {
        $this->status = self::STATUS_APPROVED;
        return $this->save(false);
    }

    public function reject()
    {
        $this->status = self::STATUS_REJECTED;
        return $this->save(false);
    }
}
