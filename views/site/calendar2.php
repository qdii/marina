<?php

use \app\models\Boat;
use \yii\helpers\ArrayHelper;
use \yii\helpers\Url;
use \yii\web\JsExpression;
use \skeeks\widget\chosen\Chosen;
use \yii2fullcalendar\yii2fullcalendar;
$this->title = 'Calendar';

//\app\assets\CalendarAsset::register($this);

$boatSelector = [
  'model'       => new Boat,
  'attribute'   => 'name',
  'placeholder' => 'Choose a boat',
  'items'       => ArrayHelper::map($boats, 'id', 'name'),
  'clientEvents' => [ 'change' => 'function(ev, p) { window.cal.on_boat_change(p); }' ]
];

$calendarOptions = [
    'ajaxEvents' => Url::toRoute(['ajax/get-meals', 'id' => 0]),
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
        'eventAfterRender'  => new JsExpression( "function(event,el) { element.html(event.title); }" ),
    ],
];

?>

<div class="page-header">
  <h1><?php echo $this->title ?></h1>
</div>

<div class="panel panel-info">
  <div class="panel-heading">
    Search
  </div>
  <div class="panel-body">
    <div class="row">
      <div class='col-lg-3'><?php echo Chosen::widget($boatSelector) ?></div>
    </div>
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

<div class="panel panel-success">
  <div class="panel-heading">
    Shopping list
  </div>
  <div class="panel-body">
  </div>
</div>
