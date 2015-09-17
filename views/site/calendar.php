<?php

use \app\models\Boat;
use \app\models\Vendor;
use \yii\helpers\ArrayHelper;
use \yii\helpers\Url;
use \yii\web\JsExpression;
use \yii\web\View;
use \skeeks\widget\chosen\Chosen;
use \yii2fullcalendar\yii2fullcalendar;
$this->title = 'Calendar';

\app\assets\CalendarAsset::register($this);

$boatSelector = [
  'model'       => new Boat,
  'attribute'   => 'name',
  'placeholder' => 'Choose a boat',
  'items'       => ArrayHelper::map($boats, 'id', 'name'),
  'clientEvents' => [ 'change' => 'function(ev, p) { window.cal.on_boat_change(p); }' ]
];

$vendorSelector = [
  'id'          => 'vendor-chosen',
  'model'       => new Vendor,
  'attribute'   => 'name',
  'placeholder' => 'Choose a shop',
  'items'       => ArrayHelper::map($vendors, 'id', 'name'),
  'clientEvents' => [ 'change' => 'function(ev, p) { window.cal.on_vendor_change(p); }' ]
];

$calendarOptions = [
    'header' =>
    [
        'center' => 'title',
        'left'   => '',
        'right'  => 'prev next',
    ],
    'clientOptions' =>
    [
        'weekends'          => true,
        'defaultView'       => 'agendaWeek',
        'editable'          => false,
        'firstDay'          => 1,
        'slotDuration'      => '00:30:00',
        'axisFormat'        => 'HH:mm',
        'allDaySlot'        => false,
        'height'            => 'auto',
        'displayEventEnd'   => false,
        'displayEventStart' => false,
        'timeFormat'        => '',
        'columnFormat'      => 'dddd',
        'eventAfterRender'  => new JsExpression( "function(event,el) { el.html(event.title); }" ),
        'events'            =>
        [
            'url'  => Url::toRoute(['ajax/get-meals-from-boat']),
            'data' => new JsExpression( "function() { return { id: window.cal.get_boat_id() }; }" )
        ],
        'dayClick'   => new JsExpression("function() { return window.cal.new_event(); }"),
        'eventClick' => new JsExpression("function() { return window.cal.modify_event(); }")
    ],
];

$this->registerJs(
    "var fetch_ingredient_list_url = '" . Url::toRoute("ajax/get-ingredient-list-from-boat") . "';\n",
    View::POS_BEGIN
);

?>

<div class="page-header">
  <h1><?php echo $this->title ?></h1>
</div>

<div class="panel panel-info">
  <div class="panel-heading">
    Search
  </div>
  <div class="panel-body">
    <?php echo Chosen::widget($boatSelector) ?>
  </div>
</div>

<div class="panel panel-success">
  <div class="panel-heading">
    Calendar
  </div>
  <div class="panel-body">
    <?php echo yii2fullcalendar::widget($calendarOptions); ?>
  </div>
</div>

<div class="panel panel-success" id="shopping-list">
  <div class="panel-heading">
    Shopping list
  </div>
  <div class="panel-body">
    <?php echo Chosen::widget($vendorSelector) ?>
    <table class="table">
      <thead>
        <tr><th>Quantity</th><th>Name</th></tr>
      </thead>
      <tbody></tbody>
    </table>
  </div>
</div>
