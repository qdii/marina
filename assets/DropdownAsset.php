<?php

namespace app\assets;

use yii\web\AssetBundle;

/**
 * @author Victor Lavaud <victor.lavaud@gmail.com>
 */
class DropdownAsset extends AssetBundle
{
    public $sourcePath = '@webroot/js';
    public $baseUrl = '@web';
    public $js = [
        'dropdown.js'
    ];
    public $depends = [
        'yii\web\JqueryAsset',
        'yii\bootstrap\BootstrapAsset',
    ];
}
