<?php
use app\components\Panel;
use app\components\BootstrapList;
use app\assets\AdminUserAsset;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

AdminUserAsset::register( $this );

$model = app\models\User::find()->one();
$users = app\models\User::find()->all();
$listItems = array_map( function($user) { return [ "label" => $user->username, "data-id" => $user->id ]; }, $users );
$listItems[] = [ "label" => '<span class="glyphicion glyphicon-plus" aria-hidden="true"></span>' ];

$form = ActiveForm::begin([
            'id'      => 'user-info-form',
            'options' => ['class' => 'form-horizontal'],
        ]);
?>
<div class="row">
<div class="col-md-2">
<?php
// SIDE-PANEL WITH ALL THE USERS
Panel::begin( [ "header" => [ "label" => "Utilisateurs" ] ] );
$user_list = BootstrapList::begin( 
    [ "items"   => $listItems,
      "id"      => "user-list",
      "options" => [ "type" => "list-group", ] 
    ] );
$user_list->end();
$this->registerJs( "initialize_user_list('" . $user_list->getID() . "','" . $form->getID() . "');", \yii\web\View::POS_READY );
Panel::end(); 

?>
</div>
<div class="col-md-8">
<?php
// FORM WITH THE USER INFORMATION
echo $form->field($model, 'username');
echo $form->field($model, 'password')->passwordInput();
?>
<div class="form-group pull-right">
<div class="col-lg-offset-1 col-lg-11">
<?= Html::submitButton('Update', ['class' => 'btn btn-primary']) ?>
</div>
</div>
<?php ActiveForm::end() ?>
</div>
