<?php

namespace app\assets;

use yii\web\AssetBundle;

/**
 * @author Victor Lavaud <victor.lavaud@gmail.com>
 */
class AdminUserAsset extends AssetBundle
{
    public $sourcePath = '@webroot/js';
    public $baseUrl = '@web';
    public $js = [
        'admin-user.js'
    ];
    public $depends = [
        'yii\web\JqueryAsset',
        'yii\bootstrap\BootstrapAsset',
    ];
    public $jsOptions = [
        \yii\web\View::POS_END,
    ];

}
