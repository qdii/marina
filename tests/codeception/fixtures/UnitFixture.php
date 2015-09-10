<?php
namespace app\tests\codeception\fixtures;

class UnitFixture extends \yii\test\ActiveFixture
{
    public $modelClass = '\app\models\Unit';
    public $depends = [];

    public function load()
    {
    }

    public function unload()
    {
    }
}
