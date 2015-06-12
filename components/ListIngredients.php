<?php

namespace app\components;

use yii\helpers\Html;
use yii\i18n\Formatter;

class ListIngredients extends \yii\bootstrap\Widget
{
    public $items   = [];
    public $headers = [ "Nom", "Quantité", "Prix" ];

    public function init()
    {
        parent::init();
    }

    private function renderHead()
    {
        $html = "";

        $html .= Html::beginTag( "thead" );
        $html .= Html::beginTag( "tr" );

        foreach( $this->headers as $header )
            $html .= Html::tag( "th", $header );

        $html .= Html::endTag( "tr" );
        $html .= Html::endTag( "thead" );

        return $html;
    }

    private function renderRows()
    {
        $html = "";

        $html .= Html::beginTag( "tbody" );

        // ingredient rows
        foreach( $this->items as $ingredient )
        {
            $html .= Html::beginTag( "tr" );
            $html .= Html::tag( "td", $ingredient['name']     );
            $html .= Html::tag( "td", $ingredient['quantity'] . ' ' . $ingredient['unitName'] );
            $html .= Html::tag( "td", $ingredient['price']    );
            $html .= Html::endTag( "tr" );
        }

        // TOTAL row
        $html .= Html::beginTag( "tr", [ "class" => "success" ] );
        $html .= Html::tag( "td", Html::tag( "strong", "Total" ) );
        $html .= Html::tag( "td", Html::tag( "strong", "" ) );
        $html .= Html::tag( "td", Html::tag( "strong", $this->total() . "€" ) );
        $html .= Html::endTag( "tr" );
        $html .= Html::endTag( "tbody" );

        return $html;
    }

    private function total()
    {
        $total = 0;
        foreach( $this->items as $ingredient )
            $total += $ingredient['price'];
        return $total;
    }

    public function run()
    {
        $html = "";
        $html .= Html::beginTag( "div", [ "class" => "container" ] );
        $html .= Html::beginTag( "table", [ "class" => "table table-hover" ] );

        $html .= $this->renderHead();
        $html .= $this->renderRows();

        $html .= Html::endTag( "table" );
        $html .= Html::endTag( "div" );

        return $html;
    }
}

?>
