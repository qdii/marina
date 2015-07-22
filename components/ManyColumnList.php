<?php

namespace app\components;

use yii\helpers\Html;
use yii\i18n\Formatter;

class ManyColumnList extends \yii\bootstrap\Widget
{
    public $items        = [];
    public $headers      = [];
    public $totals       = [];
    public $showTotal    = true;
    public $attributes   = [];
    public $data_id;

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
        $i = 0;
        foreach ( $this->items as $item ) {
            $options = [];
            if (isset($this->attributes[$i]['id'])) {
                $options['id'] = $this->attributes[$i]['id'];
            }
            $html .= Html::beginTag("tr", $options);
            foreach ( $item as $value ) {
                $html .= Html::tag("td", $value);
            }
            $html .= Html::endTag("tr");
            $i++;
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

        $tableOptions = [];
        Html::addCssClass($tableOptions, "table table-hover");
        if (count($this->data_id)) {
            $tableOptions[ 'data-id' ] = $this->data_id;
        }
        $html .= Html::beginTag("table", $tableOptions);

        $html .= $this->renderHead();
        $html .= $this->renderRows();

        $html .= Html::endTag( "table" );

        return $html;
    }
}

?>
