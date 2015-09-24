<?php

namespace app\models;

use Yii;
use yii\base\Model;

/**
 * SignupForm is the model behind the login form.
 */
class SignupForm extends Model
{
    public $username;
    public $password;
    public $email;
    public $captcha;

    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            // username and password are both required
            [['username', 'password', 'email', 'captcha'], 'required'],

            ['username', 'validateUsername'],
            ['email',    'validateEmail'],
            ['email',    'email'],
            ['captcha',  'captcha'],
        ];
    }

    public function validateUsername($attribute, $params)
    {
        if (!$this->hasErrors()) {
            return;
        }

        if (User::find()->where(['name' => $this->username])->exists()) {
            $this->addError($attribute, 'Username already taken');
        }
    }

    public function validateEmail($attribute, $params)
    {
        if (!$this->hasErrors()) {
            return;
        }

        if (User::find()->where(['email' => $this->email])->exists()) {
            $this->addError($attribute, 'Email already taken');
        }
    }
}
