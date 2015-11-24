<?php

use \app\models\Cruise;
use \app\models\Vendor;
use \app\models\Dish;
use \app\models\Meal;
use \app\models\NewMeal;
use \yii\helpers\ArrayHelper;
use \yii\helpers\Url;
use \yii\helpers\Html;
use \yii\web\JsExpression;
use \yii\web\View;
use \skeeks\widget\chosen\Chosen;
use \yii2fullcalendar\yii2fullcalendar;
use \yii\bootstrap\Modal;
use \yii\bootstrap\Button;
use \yii\widgets\ActiveForm;
use \kartik\datetime\DateTimePicker;
use \kartik\widgets\TouchSpin;

$this->title = 'Calendar';

\app\assets\CalendarAsset::register($this);

$mealPanel = [
    'header' => 'Meal editor',
    'size'   => Modal::SIZE_LARGE,
    'footer' =>
    Button::widget(['label' => 'Save', 'options' => ['class' => 'btn-success', 'type' => 'button'], 'id' => 'btn-save-meal']) .
    Button::widget(['label' => 'Delete', 'options' => ['class' => 'btn-danger', 'type' => 'button'],'id' => 'btn-delete-meal'])
];

$cruiseSelector = [
  'model'       => new Cruise,
  'attribute'   => 'name',
  'placeholder' => 'Choose a cruise',
  'items'       => ArrayHelper::map($cruises, 'id', 'name'),
  'clientEvents' => [ 'change' => 'function(ev, p) { window.cal.on_cruise_change(p); }' ]
];

$vendorSelector = [
  'id'          => 'vendor-chosen',
  'model'       => new Vendor,
  'attribute'   => 'name',
  'placeholder' => 'Choose a shop',
  'items'       => ArrayHelper::map($vendors, 'id', 'name'),
  'clientEvents' => [ 'change' => 'function(ev, p) { window.cal.on_vendor_change(p); }' ]
];

$calendarOptions = [ 'header' =>
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
            'url'  => Url::toRoute(['ajax/get-meals']),
            'data' => new JsExpression( "function() { return { id: window.cal.get_cruise_id() }; }" )
        ],
        'dayClick'   => new JsExpression("function(when) { return window.cal.new_event(when); }"),
        'eventClick' => new JsExpression("function(ev) { return window.cal.modify_event(ev.id); }")
    ],
];

$mealForm = [
  'method' => 'post',
  'action' => '#',
];

$dateOpts = [
    'pluginOptions' => [ 'weekStart' => 1 ],
    'removeButton' => false,
];

$spinOpts = [
    'pluginOptions' => [
        'initval' => 1,
        'min'     => 1,
        'max'     => 99999
    ]
];

$userOpts = [
    'items' => ArrayHelper::map($users, 'id', 'username'),
    'placeholder' => 'Pick a cook'
];
$firstCourseOpts = [
    'items'       => ArrayHelper::map($dishes, 'id', 'name'),
    'placeholder' => 'Pick a dish'
];
$secondCourseOpts = [
    'items'       => ArrayHelper::map($dishes, 'id', 'name'),
    'placeholder' => 'Pick a dish'
];
$dessertOpts = [
    'items'       => ArrayHelper::map($dishes, 'id', 'name'),
    'placeholder' => 'Pick a dessert',
];
$drinkOpts = [
    'items'       => ArrayHelper::map($dishes, 'id', 'name'),
    'placeholder' => 'Pick a drink',
];

?>

<div class="page-header">
  <h1><?php echo $this->title ?></h1>
</div>

<?php $modal = Modal::begin($mealPanel); $modalId = $modal->id;
  $mdl = new NewMeal;
  $form = ActiveForm::begin($mealForm); $formId = $form->id?>
    <div class="row">
      <div class="col-lg-6">
        <?php echo $form->field($mdl, 'date')->widget(DateTimePicker::classname(), $dateOpts); ?>
      </div>
      <div class="col-lg-2">
        <?php echo $form->field($mdl, 'nbGuests')->widget(TouchSpin::classname(), $spinOpts); ?>
      </div>
      <div class="col-lg-4">
        <?php echo $form->field($mdl, 'cook')->widget(Chosen::classname(), $userOpts); ?>
      </div>
    </div>
    <div class="row">
      <div class="col-lg-3">
        <?php echo $form->field($mdl, 'firstCourse')->widget(Chosen::classname(), $firstCourseOpts); ?>
      </div>
      <div class="col-lg-3">
        <?php echo $form->field($mdl, 'secondCourse')->widget(Chosen::classname(), $secondCourseOpts); ?>
      </div>
      <div class="col-lg-3">
        <?php echo $form->field($mdl, 'dessert')->widget(Chosen::classname(), $dessertOpts); ?>
      </div>
      <div class="col-lg-3">
        <?php echo $form->field($mdl, 'drink')->widget(Chosen::classname(), $drinkOpts); ?>
      </div>
    </div>
    <?php echo $form->field($mdl, 'cruise', [ 'options' => [ 'class' => 'hidden' ]]); ?>
    <?php echo $form->field($mdl, 'mealId', [ 'options' => [ 'class' => 'hidden' ]]);
  ActiveForm::end();
Modal::end(); ?>

<div class="panel panel-info">
  <div class="panel-heading">
    Search
  </div>
  <div class="panel-body">
    <?php echo Chosen::widget($cruiseSelector) ?>
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

<?php
$this->registerJs(
    "var fetch_ingredient_list_url = '" . Url::toRoute("ajax/get-ingredient-list") . "';\n" .
    "var get_meal_url = '" . Url::toRoute("ajax/get-meal") . "';\n" .
    "var update_meal_url = '" . Url::toRoute("ajax/update-meal") . "';\n" .
    "var new_meal_url = '" . Url::toRoute("ajax/new-meal") . "';\n" .
    "var delete_meal_url = '" . Url::toRoute("ajax/delete-meal") . "';\n" .
    "var get_cruise_url = '" . Url::toRoute("ajax/get-cruise") . "';\n" .
    "var meal_dialog_id = '#$modalId';\n" .
    "var meal_form_id = '#$formId';\n",
    View::POS_BEGIN
);
?>

