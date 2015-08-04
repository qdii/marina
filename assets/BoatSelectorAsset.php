<?php

namespace app\assets;

use yii\web\AssetBundle;

class BoatSelectorAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $js = [
    ];
    public $depends = [
        'yii\web\JqueryAsset',
    ];
    public $jsOptions = [
        \yii\web\View::POS_READY,
    ];
}
