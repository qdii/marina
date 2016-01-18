<?php
/**
 * This page lets the user look up a recipe in terms of products
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
use \app\models\Cruise;
use \kartik\date\DatePicker;
use \skeeks\widget\chosen\Chosen;

$this->title = 'Cruises';

\app\assets\CruiseAsset::register($this);

$deleteBtn = Html::button(
    '<span class="glyphicon glyphicon-trash"></span>',
    [ 'class' => 'btn btn-danger cruise-delete-btn' ]
);
$newCruiseBtn = Html::button(
    'New cruise',
    [ 'class' => 'btn btn-success', 'id' => 'cruise-new-btn' ]
);
$dateCls = DatePicker::classname();
$chosenCls = Chosen::classname();
$dateOpts = [
    'pluginOptions' => [ 'weekStart' => 1 ],
    'removeButton'  => false,
];
$chosenOpts = [
    'items'       => ArrayHelper::map($boats, 'id', 'name'),
    'placeholder' => 'Pick a boat',
];

?>

<div class="page-header">
<h1><?php echo $this->title ?></h1>
</div>

<? echo $newCruiseBtn; ?>

<table class="table" id="cruise_list">
  <thead>
    <tr>
      <th>Name</th>
      <th>Actions</th>
    </tr>
  </thead>
  <tbody id="cruise_list">
  </tbody>
</table>

<div class="hidden">
<?php
  // Form to delete a cruise
  ActiveForm::begin([
      'id' => 'delete-cruise-form',
      'method' => 'POST',
      'action' => Url::toRoute('ajax/delete-cruise'),
  ]);
  echo Html::input('text', 'cruiseId', null, ['id' => 'delete-cruise-id']);
  ActiveForm::end();
?>
</div>

<?php
  // Modal to confirm deletion
  $deleteCruiseModal = Modal::begin([
    'header' => 'Are you sure you want to delete this cruise?',
    'id'     => 'delete-cruise-confirm-modal',
  ]);
  echo Html::button(
      'Delete', [
          'class' => 'btn btn-danger',
          'id'    => 'submit-delete-cruise',
      ]
  );
  Modal::end();

  // Form to create a new cruise
  $form = ActiveForm::begin([
      'id'     => 'new-cruise-form',
      'method' => 'POST',
      'action' => Url::toRoute('ajax/new-cruise'),
  ]);
  $newCruiseModal = Modal::begin([
      'header' => 'Create a new cruise',
      'footer' => Html::submitButton('OK', ['class' => 'btn btn-success']),
      'id' => 'new-cruise-modal',
  ]);
  $model = new Cruise;
  echo $form->field($model, 'name');
  echo $form->field($model, 'dateStart')->widget($dateCls, $dateOpts);
  echo $form->field($model, 'dateFinish')->widget($dateCls, $dateOpts);
  echo $form->field($model, 'boat')->widget($chosenCls, $chosenOpts);
  Modal::end();
  ActiveForm::end();
?>

<?php
$this->registerJs(
    "var get_cruises_url = '" . Url::toRoute("ajax/get-cruises") . "';\n" .
    'var delete_cruise_modal = "#' . $deleteCruiseModal->getId() .'";' . "\n" .
    'var new_cruise_modal = "#'    . $newCruiseModal->getId() .'";' . "\n" .
    "var btn_txt = '$deleteBtn';\n",
    View::POS_BEGIN
);
?>
