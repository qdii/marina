<?php

namespace app\components;

use \yii\bootstrap\ActiveForm;
use \yii\bootstrap\Modal;
use \yii\bootstrap\Button;
use \yii\helpers\Html;
use yii\helpers\ArrayHelper;

// displays a dialog to modify / create / delete a meal
class MealDialog extends \yii\base\Widget
{
    public $title         = "Placer un repas";
    public $newMealUrl    = "";
    public $deleteMealUrl = "";
    public $model         = NULL;

    public $users;
    public $firstCourses;
    public $secondCourses;
    public $desserts;
    public $drinks;

    private $form;
    private $dialog;

    public function init()
    {
        parent::init();

        $this->getView()->registerJs(
            "function updateMealDialog( type, date, nbGuests, cook, firstCourse, secondCourse, dessert, drink )
             {
                    $( '#meal-type' )         .val ( type );
                    $( '#meal-date' )         .attr( 'value',date );
                    $( '#meal-nbguests' )     .attr( 'value',nbGuests );
                    $( '#meal-cook' )         .val ( cook );
                    $( '#meal-firstcourse' )  .val ( firstCourse );
                    $( '#meal-secondcourse' ) .val ( secondCourse );
                    $( '#meal-dessert' )      .val ( dessert );
                    $( '#meal-drink' )        .val ( drink );
            }
            function deleteMeal( meal_id )
            {
                $.ajax({
                    method: 'GET',
                    url: '" . $deleteMealUrl . "',
                    data: { id: meal_id }
                }).done(function(meal){
                    alert('deleted!');
                });
            } ",
            \yii\web\View::POS_END
        );

        $this->getView()->registerJs(
            "$( #'" . $this->getDeleteBtnID() . "' ).click(function(){ deleteMeal(); });",
            \yii\web\View::POS_LOAD
        );
    }

    public function run()
    {
        $id = $this->getID();

        $this->dialog = Modal::begin(
            [   'id'     => $id . '-new-meal-dialog',
                'header' => \yii\helpers\Html::tag( 'h4', $this->title, [ 'class' => 'modal-title' ] )
            ] );

        $this->form   = ActiveForm::begin(
            [   'id'     => $id . '-new-meal-form',
                'method' => 'POST',
                'action' => [ $this->newMealUrl ]
            ] );

        // add "Supprimer" button
        $this->dialog->footer = Html::submitButton('Supprimer',
            [   'class' => 'btn btn-danger',
                'id'    => $this->getDeleteBtnID(),
            ] );

        // add "OK" button
        $this->dialog->footer .= Html::submitButton('OK',
            [   'id'    => $id . '-new-meal-btn-ok',
                'class' => 'btn btn-primary'
            ] );

        $output  = $this->form->field($this->model, 'date'          );
        $output .= $this->form->field($this->model, 'nbGuests'      );
        $output .= $this->form->field($this->model, 'cook'          )->dropDownList( ArrayHelper::map( $this->users,          'id', 'username' ) );
        $output .= $this->form->field($this->model, 'firstCourse'   )->dropDownList( ArrayHelper::map( $this->firstCourses,   'id', 'name' ) );
        $output .= $this->form->field($this->model, 'secondCourse'  )->dropDownList( ArrayHelper::map( $this->secondCourses,  'id', 'name' ) );
        $output .= $this->form->field($this->model, 'dessert'       )->dropDownList( ArrayHelper::map( $this->desserts,       'id', 'name' ) );
        $output .= $this->form->field($this->model, 'drink'         )->dropDownList( ArrayHelper::map( $this->drinks,         'id', 'name' ) );
        $output .= $this->form->field($this->model, 'type'          )->dropDownList( [ 'lunch' => 'lunch', 'dinner' => 'dinner' ] );

        echo $output;

        ActiveForm::end();
        Modal::end();
    }

    public function getDialogID()
    {
        return $this->dialog->getID();
    }

    public function getFormID()
    {
        return $this->form->getID();
    }

    public function getDeleteBtnID()
    {
        return $this->getID() . '-new-meal-btn-delete';
    }
}

?>
