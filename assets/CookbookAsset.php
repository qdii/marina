<?php

namespace app\assets;

use yii\web\AssetBundle;

/**
 * @author Victor Lavaud <victor.lavaud@gmail.com>
 */
class CookbookAsset extends AssetBundle
{
    public $sourcePath = '@webroot/js';
    public $baseUrl = '@web';
    public $js = [
        'cookbook.js'
    ];
    public $depends = [
        'yii\web\JqueryAsset',
    ];
    public $jsOptions = [
        \yii\web\View::POS_LOAD,
    ];

}
