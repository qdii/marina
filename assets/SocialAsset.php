<?php
namespace app\assets;

use yii\web\AssetBundle;

/**
 * This asset bundle provides assets for login via facebook and so on
 *
 * @author Victor Lavaud <victor.lavaud@gmail.com>
 * @since  2.0
 */
class SocialAsset extends AssetBundle
{
    public $sourcePath = '@bower';
    public $css = [
        'bootstrap-social/bootstrap-social.css',
        'font-awesome/css/font-awesome.css'
    ];
}
