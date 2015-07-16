<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use yii\web\JsExpression;
use yii\bootstrap\Button;
use yii\bootstrap\Modal;
use yii\bootstrap\ActiveForm;
use yii\bootstrap\ActiveField;
use yii\jui\Dialog;
use app\models\Dish;
use app\models\User;
use app\models\Meal;
use app\models\Composition;
use dosamigos\datetimepicker\DateTimePicker;

/* @var $this yii\web\View */
$this->title = 'Calendar';
$this->params['breadcrumbs'][] = $this->title;

app\assets\RepasAsset::register($this);

global $all_dessert;
global $all_first;
global $all_second;
global $all_drink;
global $all_users;

$all_dessert = Dish::find()->where('Type = \'dessert\'')->all();
$all_first   = Dish::find()->where('Type = \'firstCourse\'')->all();
$all_second  = Dish::find()->where('Type = \'secondCourse\'')->all();
$all_drink   = Dish::find()->where('Type = \'drink\'')->all();
$all_users   = User::find()->all();
$all_types   = [ 'breakfast', 'lunch', 'dinner', 'snack' ];

// boat information
$boat = app\models\Boat::find()->one();

$placerRepasDlg = Modal::begin(['header' => '<h4 class="modal-title">Placer un repas</h4>'] );
Html::addCssClass($placerRepasDlg->headerOptions, 'modal-title');
$newMealId = 'new-meal';
$form = ActiveForm::begin([ 'id' => $newMealId, 'method' => 'POST', 'action' => ['site/new-meal'] ]);

// footer of the modal dialog
$placerRepasDlg->footer =
    Html::submitButton('Delete', ['class' => 'btn btn-danger',  'id' => 'delete-meal-btn']) .
    Html::submitButton('OK',     ['class' => 'btn btn-primary', 'id' => 'ok-meal-btn']);

$this->registerJs( "function onDeleteNewMeal() {
    $('#" . $newMealId ."').attr('action', '" .  Url::toRoute("delete-meal") . "&id=' + $('#meal-id').val());
}
$('#delete-meal-btn').click(function(){ onDeleteNewMeal(); });", \yii\web\View::POS_LOAD );


// main part of the modal dialog
$model       = new app\models\Meal;

echo $form->field($model, 'date')        ->widget(DateTimePicker::classname(), []);
echo $form->field($model, 'nbGuests');
echo $form->field($model, 'cook')        ->dropDownList( ArrayHelper::map( $all_users,   'id', 'username' ) );
echo $form->field($model, 'firstCourse') ->dropDownList( ArrayHelper::map( $all_first,   'id', 'name' ) );
echo $form->field($model, 'secondCourse')->dropDownList( ArrayHelper::map( $all_second,  'id', 'name' ) );
echo $form->field($model, 'dessert')     ->dropDownList( ArrayHelper::map( $all_dessert, 'id', 'name' ) );
echo $form->field($model, 'drink')       ->dropDownList( ArrayHelper::map( $all_drink,   'id', 'name' ) );

echo $placerRepasDlg->run();
ActiveForm::end();
$meals = Meal::find()->all();

$eventMaker = new app\components\EventMaker(
    ArrayHelper::index($all_dessert, "id"),
    ArrayHelper::index($all_first,   "id"),
    ArrayHelper::index($all_second,  "id"),
    ArrayHelper::index($all_drink,   "id"),
    ArrayHelper::index($all_users,   "id")
);
$events = $eventMaker->getEventsAndBilanFromMeals($meals);

$calendarOptions =
[
    'events' => $events,
    'header' =>
    [
        'center' => 'title',
        'left'   => '',
        'right'  => 'prev next',
    ],
    'clientOptions' =>
    [
        'weekends'          => true,
        'defaultView'       => 'basicWeek',
        'editable'          => false,
        'dayNames'          => ['Dimanche','Lundi','Mardi','Mercredi','Jeudi','Vendredi','Samedi'],
        'dayNamesShort'     => ['Dim','Lun','Mar','Mer','Je','Ven','Sam'],
        'monthNames'        => ['Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Août', 'Septembre', 'Octobre', 'Novembre', 'Décembre'],
        'monthNamesShort'   => ['Jan', 'Fév', 'Mars', 'Avr', 'Mai', 'Juin', 'Juil', 'Août', 'Sept', 'Oct', 'Nov', 'Déc'],
        'firstDay'          => 1,
        'slotDuration'      => '00:30:00',
        'axisFormat'        => 'HH:mm',
        'minTime'           => '12:00:00',
        'maxTime'           => '23:00:00',
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
                $.getJSON( '" . Url::toRoute( 'ajax-get-meal' ) . "', data, function(event) {
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
                    $( '#" . $newMealId . "').attr('action', '" . Url::toRoute("update-meal") . "&id=' + event.id);
                    $( '#" . $placerRepasDlg->getID() . "').modal();
                } )
            }" ),
        'dayClick'          => new JsExpression( "
            function(date,jsEvent,view)
            {
                $( '#" . $newMealId . "').attr('action', '" . Url::toRoute("new-meal") . "');
                $( '#" . $placerRepasDlg->getID() . "').modal();
            }" ),
    ],
];

?>

<h2><?php echo $boat->name ?> <small>Clipper 70</small></h2>
<?php
echo \yii2fullcalendar\yii2fullcalendar::widget($calendarOptions);

$priceComputer = new app\components\PriceComputer();
$priceComputer->nbGuests = 1;
$priceComputer->addMeals( $meals );
$price = $priceComputer->price();

// sort the list of ingredients by alphabetic order
ArrayHelper::multisort($priceComputer->ingredients, 'name');

$ingredients = $priceComputer->ingredients;
$total = 0;
$items = [];
foreach ( $ingredients as $ingredient ) {
    $total += $ingredient['price'];
    $items[]
        = [
            $ingredient['name'],
            $ingredient['quantity'] . ' g',
            $ingredient['price'] . ' €',
        ];
}
echo app\components\ManyColumnList::widget(
    [
        "items"      => $items,
        "headers"    => [ "Name", "Quantity", "Price" ],
        "attributes" => [ 'name', 'quantity', 'price' ],
        "showTotal"  => true,
        "totals"     => [ "", $total . ' €' ]
    ]
);

?>
