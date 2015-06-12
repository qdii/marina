<?php
use yii\bootstrap\Button;
use yii\bootstrap\Modal;
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
use yii\web\View;


// dialog for ingredients
$dialogIngredient         = Modal::begin( ['header' => Html::tag( 'h4', 'Ajouter un nouvel ingrédient', ['class' => 'modal-title'] ) ] );
$formIngredient           = ActiveForm::begin([ 'id' => 'new-ingredient', 'method' => 'POST', 'action' => ['site/new-ingredient'] ]);
$buttonOKIngredient       = Html::submitButton('OK', ['class' => 'btn btn-primary']);
$dialogIngredient->footer = $buttonOKIngredient;

$ingredient = new app\models\Ingredient;
echo $formIngredient->field($ingredient, 'name');
echo $dialogIngredient->run();
ActiveForm::end();

function defaultPrimary()
{
    $default = array();
    Html::addCssClass( $default, 'btn-default' );
    Html::addCssClass( $default, 'btn-primary' );
    return $default;
}

function newIngredientButton( View $view, Modal $dialogIngredient )
{
    $button = Button::begin([ 'label' => 'Ajouter un ingrédient', 'options' => defaultPrimary() ]);
    $view->registerJs( "$('#" . $button->getId() . "').click(function(){ $('#" . $dialogIngredient->getId() . "').modal(); });");
    return $button->run();
}

echo newIngredientButton( $this, $dialogIngredient );
$newMeal = Button::widget([ 'label' => 'Ajouter un repas' ]);
?>
