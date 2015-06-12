<?php
namespace app\components;

use yii\base\InvalidConfigException;
use yii\helpers\Html;

class Panel extends \yii\bootstrap\Widget
{
    public $header = [];
    public $body   = [];

    public $cssClass = [];

    public function init()
    {
        parent::init();
        Html::addCssClass( $this->options, 'panel panel-default' );
        ob_start();
    }

    public function run()
    {
        foreach ( $this->cssClass as $class )
            Html::addCssClass( $this->options, $class );

        $output = Html::beginTag( 'div', $this->options ) . "\n";
        $output .= $this->renderItems() . "\n";
        $output .= Html::endTag('div') . "\n";
        return $output;
    }

    public function renderItems()
    {
        // header
        if ( !array_key_exists( 'label', $this->header ) ) {
            throw new InvalidConfigException("The 'label' option is required in the header.");
        }
        Html::addCssClass( $headerOptions, 'panel-heading' );
        $output = Html::beginTag( 'div', $headerOptions ) . "\n";
        $output .= Html::encode( $this->header['label'] ) . "\n";
        $output .= Html::endTag('div') . "\n";

        // body
        if ( isset( $this->body['label'] ) && $this->body['label'] != "" )
        {
            Html::addCssClass( $bodyOptions, 'panel-body' );
            $output .= Html::beginTag( 'div', $bodyOptions ) . "\n";
            $output .= Html::encode( $this->body['label'] ) . "\n";
            $output .= Html::endTag('div') . "\n";
        }

        // contents 
        $output .= ob_get_clean();
        return $output; 
     }
}
