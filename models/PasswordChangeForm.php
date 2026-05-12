<?php
namespace app\models;
use Yii;
use yii\base\Model;

class PasswordChangeForm extends Model
{
    public $oldPassword;
    public $newPassword;
    public $newPasswordRepeat;
    private $user;

    public function rules()
    {
        return [
            [['oldPassword', 'newPassword', 'newPasswordRepeat'], 'required'],
            ['newPassword', 'string', 'min' => 6],
            ['newPasswordRepeat', 'compare', 'compareAttribute' => 'newPassword'],
            ['oldPassword', 'validateOldPassword'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'oldPassword' => 'Старый пароль',
            'newPassword' => 'Новый пароль',
            'newPasswordRepeat' => 'Повтор нового пароля',
        ];
    }

    public function validateOldPassword($attribute, $params)
    {
        if (!$this->hasErrors()) {
            $user = User::findOne(Yii::$app->user->identity->getId());
            if (!$user || !$user->validatePassword($this->$attribute)) {
                $this->addError($attribute, 'Неправильный старый пароль');
            }
        }
    }

    public function changePassword()
    {
        if (!$this->hasErrors()) {
            $user = Yii::$app->user->identity;
            $user->setPassword($this->newPassword);
            return $user->save();
        } else {
            return false;
        }
    }
}