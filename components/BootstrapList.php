<?php
namespace app\components;

use yii\base\InvalidConfigException;
use yii\helpers\Html;
use yii\web\View;

class BootstrapList extends \yii\bootstrap\Widget
{
    static public $acceptedTypes = [
        'unordered', 'ordered', 'unstyled', 
        'inline', 'description', 'horizontal-description',
        'list-group'
    ];

    public $items   = [];
    public $options = [];

    public function init()
    {
        parent::init();
    }

    private function getType() 
    {
        if ( !isset( $this->options['type'] ) )
            return "unordered";

        if (!in_array( $this->options['type'], BootstrapList::$acceptedTypes ) )
            throw new InvalidConfigException("The 'type' option is invalid");
        
        return $this->options['type'];
    }

    private function getAttr()
    {
        switch( $this->getType() )
        {
        case "unstyled":
            return "list-unstyled";
            break;

        case "list-group":
            return "list-group";
            break;

        case "inline":
            return "list-inline";
            break;

        case "horizontal-description":
            return "dl-horizontal";
            break;
        }

        return "";
    }

    private function getTag()
    {
        switch(  $this->getType() )
        {
        case "unordered":
        case "unstyled":
        case "list-group":
        case "inline":
            return "ul";
            break;

        case "ordered":
            return "ol";
            break;

        case "horizontal-description":
        case "description":
            return "dl";

        default:
            assert( 0 && "valeur de type invalide" );
        }
        return "";
    }

    private function getSubTags()
    {
        switch(  $this->getType() )
        {
        case "list-group":
        case "unordered":
        case "unstyled":
        case "ordered":
        case "inline":
            return [ "li" ];
            break;

        case "description":
        case "horizontal-description":
            return [ "dd", "dt" ];
            break;

        default:
            assert( 0 && "valeur de type invalide" );
        }
        return "";
    }

    public function run()
    {
        $attrClass = $this->getAttr();
        $tag       = $this->getTag();

        Html::addCssClass( $options, $attrClass );
        $options['id'] = $this->getID();
        $output = Html::beginTag( $tag, $options ) . "\n";
        $output .= $this->renderItems() . "\n";
        $output .= Html::endTag( $tag ) . "\n";
        return $output;
    }

    public function renderItems()
    {
        $type = $this->getType();
        $options = []; 
        $output = "";

        foreach ( $this->items as $item )
        {
            if ( !array_key_exists( 'label', $item ) )
                throw new InvalidConfigException("The 'label' option is required in the header.");

            if ( isset( $item['data-id'] ) )
                $options['data-id'] = $item['data-id'];

            switch( $type )
            {
            case "list-group":
                Html::addCssClass( $options, "list-group-item" );
            case "unordered":
            case "unstyled":
            case "ordered":
            case "inline":
                $output .= Html::beginTag( "li", $options ) . "\n";
                $output .= $item['label']         . "\n";
                $output .= Html::endTag( "li" )   . "\n";
                break;

            case "description":
            case "horizontal-description":
                $output .= Html::beginTag( "dt" ) . "\n";
                $output .= $item['term']          . "\n";
                $output .= Html::endTag( "dt" )   . "\n";
                $output .= Html::beginTag( "dd" ) . "\n";
                $output .= $item['label']         . "\n";
                $output .= Html::endTag( "dd" )   . "\n";
                break;

            default:
                assert( 0 && "valeur de type invalide" );
            }
        }

        return $output; 
     }
}
