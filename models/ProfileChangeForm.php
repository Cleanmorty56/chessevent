<?php

namespace app\models;

use yii\base\Model;

class ProfileChangeForm extends Model
{
    public $email;
    public $first_name;
    public $last_name;

    public function rules()
    {
        return [
            ['email', 'required'],
            ['email', 'email'],
            ['email', 'string', 'max' => 255],
            [['first_name', 'last_name'], 'string', 'max' => 255],
            [['first_name', 'last_name'], 'filter', 'filter' => 'trim'],
            [['first_name', 'last_name'], 'match', 'pattern' => '/^[а-яА-ЯёЁ\s\-]+$/u', 'message' => 'Только символы русского алфавита!'],
        ];
    }

    public function update($user)
    {
        if ($this->validate()) {
            $user->email = $this->email;
            $user->first_name = $this->first_name;
            $user->last_name = $this->last_name;
            return $user->save();
        }
        return false;
    }
}