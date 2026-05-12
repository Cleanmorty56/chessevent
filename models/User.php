<?php

namespace app\models;


use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\db\Expression;


class User extends ActiveRecord implements \yii\web\IdentityInterface
{


    private static $users;


    /**
     * {@inheritdoc}
     */
    public static function findIdentity($id)
    {
        return User::findOne($id);
    }

    /**
     * {@inheritdoc}
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        foreach (self::$users as $user) {
            if ($user['accessToken'] === $token) {
                return new static($user);
            }
        }

        return null;
    }

    /**
     * Finds user by username
     *
     * @param string $username
     * @return static|null
     */
    public static function findByUsername($username)
    {
        return static::findOne(['username' => $username]);
    }

    /**
     * {@inheritdoc}
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * {@inheritdoc}
     */
    public function getAuthKey()
    {
        // return $this->authKey;
    }

    /**
     * {@inheritdoc}
     */
    public function validateAuthKey($authKey)
    {
        //return $this->authKey === $authKey;
    }

    /**
     * Validates password
     *
     * @param string $password password to validate
     * @return bool if password provided is valid for current user
     */
    public function validatePassword($password)
    {
        return \Yii::$app->security->validatePassword($password, $this->password);
    }


    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::class,
                //'createdAtAttribute' => 'created_at',
                //'updatedAtAttribute' => 'updated_at',
                'value' => new Expression('NOW()'),
            ],
        ];
    }

    public function setPassword($password)
    {
        $this->password = \Yii::$app->security->generatePasswordHash($password);
    }

    public function getPlanning()
    {
        return $this->hasMany(Planning::class, ['user_id' => 'id']);
    }


    public function getRegToTournaments()
    {
        return $this->hasMany(RegToTournament::class, ['user_id' => 'id']);
    }

    public function getTournaments()
    {
        return $this->hasMany(Tournament::class, ['id' => 'tournament_id'])
            ->via('regToTournaments');
    }

    public function attributeLabels()
    {
        return [
            'id' => 'Идентификатор шахматиста',
            'first_name' => 'Имя',
            'last_name' => 'Фамилия',
            'username' => 'Логин',
            'email' => 'Почта',
            'region_id' => 'Регион',
            'elo' => 'Эло',
            'created_at' => 'Был зарегистрирован',
            'updated_at' => 'Время последнего изменения',
        ];
    }

    public function getRegion()
    {
        return $this->hasOne(Region::class, ['id' => 'region_id']);
    }
}