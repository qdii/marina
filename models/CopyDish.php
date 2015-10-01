<?php

namespace app\models;

use Yii;
use yii\base\Model;

/**
 * LoginForm is the model behind the login form.
 */
class CopyDish extends Model
{
    public $id;
    public $name;
    public $type;

    public function rules()
    {
        return [
            [['id', 'name', 'type'], 'required'],
            [['id', 'type'], 'integer'],
            ['name', 'string'],
        ];
    }
}

