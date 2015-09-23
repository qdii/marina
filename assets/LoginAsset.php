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
    public $depends = [
        'app\assets\SocialAsset',
    ];
}
