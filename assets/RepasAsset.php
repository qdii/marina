<?php

namespace app\assets;

use yii\web\AssetBundle;

/**
 * @author Victor Lavaud <victor.lavaud@gmail.com>
 */
class RepasAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $js = [
        'js/repas.js'
    ];
    public $depends = [
        'app\assets\DropdownAsset',
        'yii\web\JqueryAsset',
        'yii\bootstrap\BootstrapAsset',
    ];
    public $jsOptions = [
        \yii\web\View::POS_LOAD,
    ];
    public $css = [
        'css/repas.css'
    ];

}
