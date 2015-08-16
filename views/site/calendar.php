<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use yii\web\JsExpression;
use yii\bootstrap\Button;
use yii\bootstrap\Modal;
use yii\bootstrap\ActiveForm;
use yii\bootstrap\ActiveField;
use app\models\Dish;
use app\models\Boat;
use app\models\User;
use app\models\Meal;
use app\models\Composition;
use dosamigos\datetimepicker\DateTimePicker;
use kartik\widgets\TouchSpin;
use kartik\color\ColorInput;

/* @var $this yii\web\View */
$this->title = 'Calendar';

app\assets\RepasAsset::register($this);

/**
 * Check if a dish is a first course
 *
 * @param Dish $dish The dish to check
 *
 * @return true if the dish is a first course
 */
function isFirstCourse(Dish $dish)
{
    return $dish->type === 'firstCourse';
}

/**
 * Check if a dish is a second course
 *
 * @param Dish $dish The dish to check
 *
 * @return true if the dish is a second course
 */
function isSecondCourse($dish)
{
    return $dish->type === 'secondCourse';
}

/**
 * Check if a dish is a drink
 *
 * @param Dish $dish The dish to check
 *
 * @return true if the dish is a drink
 */
function isDrink($dish)
{
    return $dish->type === 'drink';
}

/**
 * Check if a dish is a dessert
 *
 * @param Dish $dish The dish to check
 *
 * @return true if the dish is a dessert
 */
function isDessert($dish)
{
    return $dish->type === 'dessert';
}

// NEW BOAT FORM
$title  = Html::tag('h4', 'Create a new boat');
$dialog = Modal::begin(['header' => $title]);
$form   = ActiveForm::begin([ 'action' => ['site/new-boat'] ]);
$model  = new \app\models\Boat();
echo $form->field($model, 'name');
echo Html::submitButton('Create', ['class' => 'btn btn-primary']);
ActiveForm::end();
Modal::end();
$newBoatDialogId = $dialog->getId();

// BOAT SELECTOR ICON
echo \app\components\BoatSelector::widget(
    [
        'selectedBoat'   => $boat,
        'boats'          => $boats,
        'onNewBoat'      => new JsExpression("function() { $('#$newBoatDialogId').modal('show'); }"),
        'onBoatSelected' => new JsExpression(
            "function(event) {
                var id = $(this).attr('data-id');
                window.location.href = '" . Url::toRoute("calendar") . "&id=' + id;
            }"
        ),
    ]
);

$firsts   = array_filter($dishes, 'isFirstCourse');
$seconds  = array_filter($dishes, 'isSecondCourse');
$drinks   = array_filter($dishes, 'isDrink');
$desserts = array_filter($dishes, 'isDessert');

$placerRepasTitle = Html::tag('h4', 'Placer un repas');
$placerRepasDlg = Modal::begin(['header' => $placerRepasTitle]);
Html::addCssClass($placerRepasDlg->headerOptions, 'modal-title');
$newMealId = 'new-meal';
$form = ActiveForm::begin(
    [
        'id' => $newMealId,
        'method' => 'POST',
        'action' => ['site/new-meal']
    ]
);

// footer of the modal dialog
$deleteBtn = Html::submitButton(
    'Delete',
    ['class' => 'btn btn-danger', 'id' => 'delete-meal-btn']
);
$okBtn = Html::submitButton(
    'Ok',
    ['class' => 'btn btn-primary', 'id' => 'ok-meal-btn']
);
$placerRepasDlg->footer = $deleteBtn . $okBtn;


$this->registerJs(
    "function onDeleteNewMeal() {
        $('#" . $newMealId ."').attr('action', '" .  Url::toRoute(["ajax/delete-meal"]) .
        "&id=' + $('#meal-id').val());
    }
    $('#delete-meal-btn').click(function(){ onDeleteNewMeal(); });
    $('button').removeClass('ui-widget ui-state-default ui-button ui-corner-all ui-button-text-only');",
    \yii\web\View::POS_LOAD
);

// main part of the modal dialog
$model = new app\models\Meal;
$cruiseId = $cruise ? $cruise->id : 0;
$spinOpts = [
    'pluginOptions' => [
        'initval' => 1,
        'min'     => 1,
        'max'     => 99999
    ]
];

$datepickerOpts
    = [
        'clientOptions' => [
            'weekStart' => 1
        ]
    ];
echo $form->field($model, 'date')        ->widget(DateTimePicker::classname(), $datepickerOpts);
echo $form->field($model, 'nbGuests')    ->widget(TouchSpin::classname(), $spinOpts);
echo $form->field($model, 'cook')        ->dropDownList( ArrayHelper::map( $users,   'id', 'username' ) );
echo $form->field($model, 'firstCourse') ->dropDownList( ArrayHelper::map( $firsts,   'id', 'name' ) );
echo $form->field($model, 'secondCourse')->dropDownList( ArrayHelper::map( $seconds,  'id', 'name' ) );
echo $form->field($model, 'dessert')     ->dropDownList( ArrayHelper::map( $desserts, 'id', 'name' ) );
echo $form->field($model, 'drink')       ->dropDownList( ArrayHelper::map( $drinks,   'id', 'name' ) );
echo $form->field($model, 'backgroundColor')->widget(ColorInput::classname());
echo '<input name="meal-id" id="meal-id" type="hidden" value="0"/>';
echo $form->field($model, 'cruise', [ 'options' => [ 'class' => 'hidden' ]]);

echo $placerRepasDlg->run();
ActiveForm::end();

$eventMaker = new app\components\EventMaker(
    ArrayHelper::index($desserts, "id"),
    ArrayHelper::index($firsts,   "id"),
    ArrayHelper::index($seconds,  "id"),
    ArrayHelper::index($drinks,   "id"),
    ArrayHelper::index($users,    "id"),
    $ingredients,
    $compositions,
    $units,
    $dishes,
    $meals
);

$calendarOptions =
[
    'ajaxEvents' => Url::toRoute(['ajax/get-meals', 'id' => $cruiseId]),
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
        'dayNames'          => ['Dimanche','Lundi','Mardi','Mercredi','Jeudi','Vendredi','Samedi'],
        'dayNamesShort'     => ['Dim','Lun','Mar','Mer','Je','Ven','Sam'],
        'monthNames'        => ['Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Août', 'Septembre', 'Octobre', 'Novembre', 'Décembre'],
        'monthNamesShort'   => ['Jan', 'Fév', 'Mars', 'Avr', 'Mai', 'Juin', 'Juil', 'Août', 'Sept', 'Oct', 'Nov', 'Déc'],
        'firstDay'          => 1,
        'slotDuration'      => '00:30:00',
        'axisFormat'        => 'HH:mm',
        'allDaySlot'        => false,
        'height'            => 'auto',
        'displayEventEnd'   => false,
        'displayEventStart' => false,
        'timeFormat'        => '',
        'columnFormat'      => 'dddd',
        'eventAfterRender'  => new JsExpression( "function(event,element) { element.html(event.title); }" ),
        'eventClick'        => new JsExpression( "
            function(event,jsEvent,view)
            {
                if (!('id' in event)) {
                    return;
                }

                var data = { id: event.id };
                $.getJSON( '" . Url::toRoute( ['ajax/get-meal'] ) . "', data, function(event) {
                    var date=moment(event.date,'YYYY-MM-DD HH-mm');
                    var day=date.format('YYYY-MM-DD HH:mm');
                    $( '#meal-date' ).val(day);
                    $( '#meal-nbguests' ).attr( 'value', event.nbGuests );
                    $( '#meal-cook' ).val( event.cook );
                    $( '#meal-firstcourse' ).val( event.firstCourse );
                    $( '#meal-secondcourse' ).val( event.secondCourse );
                    $( '#meal-dessert' ).val( event.dessert );
                    $( '#meal-drink' ).val( event.drink );
                    $( '#meal-id' ).val( event.id );
                    $( '#meal-cruise' ).attr('value', '$cruiseId');
                    $( '#meal-backgroundcolor' ).attr('value', event.backgroundColor);
                    $( '#" . $newMealId . "').attr('action', '" . Url::toRoute(["ajax/update-meal"]) . "&id=' + event.id);
                    $( '#" . $placerRepasDlg->getID() . "').modal();
                } )
            }" ),
        'dayClick'          => new JsExpression( "
            function(date,jsEvent,view)
            {
                $( '#$newMealId').attr('action', '" . Url::toRoute("new-meal") . "');
                $( '#meal-cruise' ).attr('value', '$cruiseId');
                $( '#" . $placerRepasDlg->getID() . "').modal();
            }" ),
    ],
];

echo \yii2fullcalendar\yii2fullcalendar::widget($calendarOptions);

$priceComputer = new app\components\PriceComputer(
    $ingredients,
    $compositions,
    $units,
    $dishes,
    $meals
);
$priceComputer->addMeals($meals);

// sort the list of ingredients by alphabetic order
ArrayHelper::multisort($priceComputer->items, 'name');

?>
<table class="table table-hover">
    <thead>
        <th>Name</th>
        <th>Quantity (in g)</th>
        <th>In unit</th>
        <th>Unit</th>
    </thead>
    <tbody>
        <?php foreach ($priceComputer->items as $it) { ?>
        <tr>
            <td><?php echo $it['name'] ?></td>
            <td><?php echo $it['quantity'] ?></td>
            <td><?php echo "" ?></td>
            <td><?php echo "" ?></td>
        </tr>
        <?php } ?>
    </tbody>
</table>
