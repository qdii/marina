<?php

namespace app\assets;

use yii\web\AssetBundle;

/**
 * @author Victor Lavaud <victor.lavaud@gmail.com>
 */
class BoatAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $js = [
        'js/boat.js'
    ];
    public $depends = [
        'yii\web\JqueryAsset',
        'app\assets\JqueryFormAsset'
    ];
    public $jsOptions = [
        \yii\web\View::POS_LOAD,
    ];
    public $css = [
        'css/boat.css'
    ];
}
