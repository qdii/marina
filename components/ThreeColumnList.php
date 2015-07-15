<?php

namespace app\components;

use yii\helpers\Html;
use yii\i18n\Formatter;

class ThreeColumnList extends \yii\bootstrap\Widget
{
    public $items        = [];
    public $headers      = [];
    public $totals       = [];
    public $showTotal    = true;
    public $attributes   = [];

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

        $html .= Html::beginTag("tbody");

        // item rows
        foreach ( $this->items as $item ) {
            $html .= Html::beginTag("tr");
            foreach ( $item as $value ) {
                $html .= Html::tag("td", $value);
            }
            $html .= Html::endTag("tr");
        }

        // TOTAL row
        if ($this->showTotal) {
            $html .= Html::beginTag("tr", [ "class" => "success" ]);
            $html .= Html::tag("td", Html::tag("strong", "Total"));
            foreach ( $this->totals as $total ) {
                $html .= Html::tag("td", Html::tag("strong", $total));
            }
            $html .= Html::endTag("tr");
        }

        $html .= Html::endTag("tbody");

        return $html;
    }

    public function run()
    {
        $html = "";
        $html .= Html::beginTag( "table", [ "class" => "table table-hover" ] );

        $html .= $this->renderHead();
        $html .= $this->renderRows();

        $html .= Html::endTag( "table" );

        return $html;
    }
}

?>
