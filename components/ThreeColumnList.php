<?php

namespace app\components;

use yii\helpers\Html;
use yii\i18n\Formatter;

class ThreeColumnList extends \yii\bootstrap\Widget
{
    public $items        = [];
    public $headers      = [];
    public $showTotal0   = true;
    public $showTotal1   = true;
    public $total0       = 0;
    public $total1       = 0;
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

        $html .= Html::beginTag( "tbody" );

        // ingredient rows
        foreach( $this->items as $ingredient )
        {
            $value0 = $ingredient[$this->attributes[0]];
            $value1 = $ingredient[$this->attributes[1]];
            $value2 = $ingredient[$this->attributes[2]];

            $html .= Html::beginTag( "tr" );
            $html .= Html::tag( "td", $value0 );
            $html .= Html::tag( "td", $value1 );
            $html .= Html::tag( "td", $value2 );
            $html .= Html::endTag( "tr" );
        }

        // TOTAL row
        if ($this->showTotal0 || $this->showTotal1) {
            $html .= Html::beginTag( "tr", [ "class" => "success" ] );
            $html .= Html::tag( "td", Html::tag( "strong", "Total" ) );
            $html .= Html::tag( "td", Html::tag( "strong", $this->showTotal0 ? $this->total0 : "" ) );
            $html .= Html::tag( "td", Html::tag( "strong", $this->showTotal1 ? $this->total1 : "" ) );
            $html .= Html::endTag( "tr" );
            $html .= Html::endTag( "tbody" );
        }

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
