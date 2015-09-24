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
        ];
    }
}
