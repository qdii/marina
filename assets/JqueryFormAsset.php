<?php
namespace app\assets;

use yii\web\AssetBundle;

/**
 * This asset bundle provides the jquery form javascript library
 *
 * @author Victor Lavaud <victor.lavaud@gmail.com>
 * @since  2.0
 */
class JqueryFormAsset extends AssetBundle
{
    public $sourcePath = '@bower/jquery-form';
    public $js = [
        'jquery.form.js',
    ];
}
