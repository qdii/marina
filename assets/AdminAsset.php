<?php

namespace app\assets;

use yii\web\AssetBundle;

/**
 * @author Victor Lavaud <victor.lavaud@gmail.com>
 */
class AdminAsset extends AssetBundle
{
    public $sourcePath = '@webroot/js';
    public $baseUrl = '@web';
    public $js = [
        'admin.js'
    ];
    public $depends = [
        'yii\web\JqueryAsset',
        'yii\bootstrap\BootstrapAsset',
    ];
    public $jsOptions = [
        \yii\web\View::POS_LOAD,
    ];

}
