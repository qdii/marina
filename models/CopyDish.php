<?php

namespace app\models;

use Yii;
use yii\base\Model;

/**
 * CopyDish is a custom model to permit copying dish.
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

