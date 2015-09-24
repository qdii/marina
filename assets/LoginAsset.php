<?php

namespace app\assets;

use yii\web\AssetBundle;

/**
 * @author Victor Lavaud <victor.lavaud@gmail.com>
 */
class LoginAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $css = [
        'css/login.css'
    ];
    public $js = [
        'js/login.js'
    ];
    public $jsOptions = [
        \yii\web\View::POS_LOAD,
    ];
    public $depends = [
        'yii\web\JqueryAsset',
        'app\assets\SocialAsset',
    ];
}
