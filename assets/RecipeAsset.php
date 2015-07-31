<?php

namespace app\assets;

use yii\web\AssetBundle;

class RecipeAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $js = [
        'js/bilan.js'
    ];
    public $depends = [
        'yii\web\JqueryAsset',
        'app\assets\JqueryFormAsset',
    ];
    public $jsOptions = [
        \yii\web\View::POS_READY,
    ];
}
