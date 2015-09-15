<?php

namespace app\assets;

use yii\web\AssetBundle;

/**
 * @author Victor Lavaud <victor.lavaud@gmail.com>
 */
class CalendarAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $js = [
        'js/calendar.js'
    ];
    public $depends = [
        'yii\web\JqueryAsset',
    ];
    public $jsOptions = [
        \yii\web\View::POS_LOAD,
    ];
    public $css = [
        'css/calendar.css'
    ];
}
