<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\db\ActiveRecord;

class Signup extends ActiveRecord
{

    public $username;
    public $first_name;
    public $last_name;
    public $email;
    public $elo;
    public $region_id;
    public $password;

    public $password_repeat;


    public function rules()
    {
        return [
            [['elo'], 'default', 'value' => 1000],
            [['username', 'email', 'first_name', 'last_name', 'password', 'region_id'], 'required'],
            [['email'], 'email'],
            ['password_repeat', 'compare', 'compareAttribute' => 'password'],
            [['first_name', 'last_name'], 'match', 'pattern' => '/^[а-яА-ЯёЁ\s\-]+$/u', 'message' => 'Только символы русского алфавита!'],
            ['username', 'match', 'pattern' => '/^[a-zA-Z0-9]+$/', 'message' => 'Логин должен состоять из символов латинского алфавита и цифр'],
            ['username', 'unique',
                'targetClass' => User::class,
                'message' => 'Этот логин уже занят.'],
            ['email', 'unique',
                'targetClass' => User::class,
                'message' => 'Пользователь с таким эл. адресом уже существует в системе.'],
            [['username', 'password'], 'string', 'min' => 8]
        ];
    }

    public function signup()
    {
        if (!$this->validate()) {
            return null;
        }

        $user = new User();
        $user->username = $this->username;
        $user->first_name = $this->first_name;
        $user->last_name = $this->last_name;
        $user->email = $this->email;
        $user->elo = $this->elo;
        $user->region_id = $this->region_id;
        $user->password = Yii::$app->getSecurity()->generatePasswordHash($this->password);

        return $user->save() ? $user : null;
    }

    public function attributeLabels()
    {
        return [
            'username' => 'Логин',
            'first_name' => 'Имя',
            'last_name' => 'Фамилия',
            'elo' => 'Эло (рейтинг)',
            'password' => 'Пароль',
            'password_repeat' => 'Повтор пароля',
            'region_id' => 'Выберите регион'
        ];
    }
}