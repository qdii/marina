<?php
/**
 * This page lets you manage the different boats.
 *
 * PHP version 5.4
 *
 * @category Views
 * @package  Views
 * @author   Victor Lavaud (qdii) <victor.lavaud@gmail.com>
 * @license  GPLv2 https://www.gnu.org/licenses/gpl-2.0.html
 * @link     http://marina.dodges.it
 *
 */

use \yii\helpers\Url;
use \yii\helpers\ArrayHelper;
use \yii\bootstrap\Html;
use \yii\bootstrap\Modal;
use \yii\web\View;
use \yii\widgets\ActiveForm;
use \app\models\Boat;
use \app\models\Cruise;
use \kartik\date\DatePicker;
use \skeeks\widget\chosen\Chosen;

$this->title = 'Boats';

\app\assets\BoatAsset::register($this);

$deleteBtn = Html::button(
    '<span class="glyphicon glyphicon-trash"></span>',
    [ 'class' => 'btn btn-danger boat-delete-btn' ]
);
$newBoatBtn = Html::button(
    'New boat',
    [ 'class' => 'btn btn-success', 'id' => 'boat-new-btn' ]
);
?>

<div class="page-header">
<h1><?php echo $this->title ?></h1>
</div>

<? echo $newBoatBtn; ?>

<table class="table">
  <thead>
    <tr>
      <th>Name</th>
      <th>Actions</th>
    </tr>
  </thead>
  <tbody id="boat_list">
  </tbody>
</table>

<div class="hidden">
<?php
  // Form to delete a boat
  ActiveForm::begin([
      'id' => 'delete-boat-form',
      'method' => 'POST',
      'action' => Url::toRoute('ajax/delete-boat'),
  ]);
  echo Html::input('text', 'boatId', null, ['id' => 'delete-boat-id']);
  ActiveForm::end();
?>
</div>

<?php
  // Modal to confirm deletion
  $deleteBoatModal = Modal::begin([
    'header' => 'Are you sure you want to delete this boat?',
    'id'     => 'delete-boat-confirm-modal',
  ]);
  echo Html::button(
      'Delete', [
          'class' => 'btn btn-danger',
          'id'    => 'submit-delete-boat',
      ]); Modal::end(); // Form to create a new boat
  $form = ActiveForm::begin([
      'id'     => 'new-boat-form',
      'method' => 'POST',
      'action' => Url::toRoute('ajax/new-boat'),
  ]);
  $newBoatModal = Modal::begin([
      'header' => 'Create a new boat',
      'footer' => Html::submitButton('OK', [
          'class' => 'btn btn-success',
          'id'    => 'submit-boat-btn',
      ]),
      'id' => 'new-boat-modal',
  ]);
  echo $form->field(new Boat, 'name');
  Modal::end();
  ActiveForm::end();
?>

<?php
$this->registerJs(
    "var get_boats_url = '" . Url::toRoute("ajax/get-boats") . "';\n" .
    'var delete_boat_modal = "#' . $deleteBoatModal->getId() .'";' . "\n" .
    'var new_boat_modal = "#'    . $newBoatModal->getId() .'";' . "\n" .
    "var btn_txt = '$deleteBtn';\n",
    View::POS_BEGIN
);
?>
