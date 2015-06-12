<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use yii\web\JsExpression;
use yii\bootstrap\ActiveForm;
use yii\bootstrap\Button;
use yii\bootstrap\Modal;
use yii\jui\Dialog;
use app\models\Dish;
use app\models\User;
use app\models\Meal;
use app\models\Composition;

/* @var $this yii\web\View */
$this->title = 'Repas';
$this->params['breadcrumbs'][] = $this->title;

app\assets\RepasAsset::register($this);

global $all_dessert ;  global $dessert_by_id ;
global $all_first   ;  global $first_by_id   ;
global $all_second  ;  global $second_by_id  ;
global $all_drink   ;  global $drink_by_id   ;
global $all_users   ;  global $user_by_id    ;

// retrieve all the desserts
$all_dessert = Dish::find()->where('Type = \'dessert\'')->all();       $dessert_by_id = ArrayHelper::index($all_dessert, "id" );
$all_first   = Dish::find()->where('Type = \'firstCourse\'')->all();   $first_by_id   = ArrayHelper::index($all_first  , "id" );
$all_second  = Dish::find()->where('Type = \'secondCourse\'')->all();  $second_by_id  = ArrayHelper::index($all_second , "id" );
$all_drink   = Dish::find()->where('Type = \'drink\'')->all();         $drink_by_id   = ArrayHelper::index($all_drink  , "id" );
$all_users   = User::find()->all();                                    $user_by_id    = ArrayHelper::index($all_users  , "id" );

function buildEventTitleFromMeal( app\models\Meal $meal )
{
    global $dessert_by_id;
    global $first_by_id  ;
    global $second_by_id ;
    global $drink_by_id  ;
    global $user_by_id   ;

    $user         = $user_by_id   [ $meal->cook ];
    $firstCourse  = $first_by_id  [ $meal->firstCourse ];
    $secondCourse = $second_by_id [ $meal->secondCourse ];
    $dessert      = $dessert_by_id[ $meal->dessert ];
    $drink        = $drink_by_id  [ $meal->drink ];

    return $menu = 'Cuisinier : ' . $user->username . '<br/>' . $meal->nbGuests . ' personne(s)' .
        Html::ul( [ $firstCourse->name, $secondCourse->name, $dessert->name, $drink->name ] );
}

function buildEventFromMeal( app\models\Meal $meal )
{
    $isLunch = $meal->type == 'lunch';

    return [
        'id'    => $meal->id,
        'title' => buildEventTitleFromMeal( $meal ),
        'start' => $meal->date . "T" . ( $isLunch ? "13:00:00Z" : "19:00:00Z" ),
        'end'   => $meal->date . "T" . ( $isLunch ? "16:00:00Z" : "22:00:00Z" ),
    ];
}

// boat information
$boat = app\models\Boat::find()->one();

$placerRepasDlg = Modal::begin(['header' => '<h4 class="modal-title">Placer un repas</h4>'] );
Html::addCssClass($placerRepasDlg->headerOptions, 'modal-title');
$form = ActiveForm::begin([ 'id' => 'new-meal', 'method' => 'POST', 'action' => ['site/new-meal'] ]);

// footer of the modal dialog
$placerRepasDlg->footer = Html::submitButton('OK', ['class' => 'btn btn-primary']);

// main part of the modal dialog
$model       = new app\models\Meal;

echo $form->field($model, 'date');
echo $form->field($model, 'nbGuests');
echo $form->field($model, 'cook')        ->dropDownList( ArrayHelper::map( $all_users,   'id', 'username' ) );
echo $form->field($model, 'firstCourse') ->dropDownList( ArrayHelper::map( $all_first,   'id', 'name' ) );
echo $form->field($model, 'secondCourse')->dropDownList( ArrayHelper::map( $all_second,  'id', 'name' ) );
echo $form->field($model, 'dessert')     ->dropDownList( ArrayHelper::map( $all_dessert, 'id', 'name' ) );
echo $form->field($model, 'drink')       ->dropDownList( ArrayHelper::map( $all_drink,   'id', 'name' ) );
echo $form->field($model, 'type')        ->dropDownList( [ 'lunch' => 'lunch', 'dinner' => 'dinner' ] );

echo $placerRepasDlg->run();
ActiveForm::end();
$meals = Meal::find()->all();

$calendarOptions =
[
    'events' => array_map( "buildEventFromMeal", $meals ),
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
            }" ),
        'dayClick'          => new JsExpression( "
            function(date,jsEvent,view)
            {
                $( '#meal-type' ).val( ( parseInt( date.format('HH')) < 13 ) ? 'lunch' : 'dinner');
                $( '#meal-date' ).attr('value', date.format('YYYY-MM-DD'));
                $( '#" . $placerRepasDlg->getID() . "').modal();
            }" ),
    ],
];

?>

<h2><?php echo $boat->name ?> <small>Frégate</small></h2>
<?php
echo \yii2fullcalendar\yii2fullcalendar::widget($calendarOptions);

$priceComputer = new app\components\PriceComputer();
$priceComputer->nbGuests = 1;
$priceComputer->addMeals( $meals );
$price = $priceComputer->price();

echo app\components\ListIngredients::widget(["items" => $priceComputer->ingredients]);

?>
