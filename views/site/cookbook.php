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

use \skeeks\widget\chosen\Chosen;
use \yii\helpers\ArrayHelper;
use \kartik\widgets\TouchSpin;
use \yii\helpers\Url;
use \yii\web\View;

\app\assets\CookbookAsset::register($this);

$boatSelectorId = 'boat-name';
$boatSelector = [
  'model'       => new \app\models\Boat,
  'attribute'   => 'name',
  'placeholder' => 'Choose a boat',
  'items'       => ArrayHelper::map($boats, 'id', 'name'),
  'clientEvents' =>
  [
      'change' => 'function(ev, params) {
          if (params === undefined) {
             window.ckbook.boat_id = 0;
          } else {
             window.ckbook.boat_id = params.selected;
          }
          window.ckbook.refresh_list();
        }'
  ]
];

$vendorSelector = [
  'id'          => 'vendor-chosen',
  'model'       => new \app\models\Vendor,
  'attribute'   => 'name',
  'placeholder' => 'Choose a shop',
  'items'       => ArrayHelper::map($vendors, 'id', 'name'),
  'clientEvents' =>
  [
      'change' => 'function(ev, params) {
          if (params === undefined) {
             window.ckbook.vendor_id = 0;
          } else {
             window.ckbook.vendor_id = params.selected;
          }
          window.ckbook.refresh_list();
        }'
  ]
];

$this->registerJs(
    "var cookbook_url = '" . Url::toRoute("ajax/get-cookbook") . "';\n"  .
    "var boat_selector = '$boatSelectorId';\n",
    View::POS_BEGIN
);

$touchSpin = [
  'id' => 'nb-guests-touchspin',
  'name'  => 'nb-guests',
  'options' => [
      'placeholder' => 'Nb of guests'
  ],
  'class' => 'form-control',
  'pluginEvents' =>
  [
    'change' => 'function(ev,params) {
      window.ckbook.guests = parseInt($(this).val());
      window.ckbook.refresh_list();
    }'
  ]
];

$this->title = 'Cookbook';
?>

<div class="page-header">
<h1><?php echo $this->title ?></h1>
</div>

<div class="row">
  <div class="col-lg-9" role="main">
    <div class="panel panel-info">
      <div class="panel-heading">
        Settings
      </div>
      <div class="panel-body">
        <div class="row">
          <div class='col-lg-5'><?php echo Chosen::widget($boatSelector) ?></div>
          <div class='col-lg-5'><?php echo Chosen::widget($vendorSelector) ?></div>
          <div class='col-lg-2'><?php echo Touchspin::widget($touchSpin) ?></div>
        </div>
      </div>
    </div>

    <div id='recipe-container'>
    </div>

    <div id="recipe-template" style="display:none;">
      <div class="panel panel-success">
        <div class="panel-heading">
        </div>
        <table class="table table-hover">
          <thead>
            <tr><th>Quantity</th><th>Product</th></tr>
          </thead>
          <tbody></tbody>
        </table>
      </div>
    </div>
  </div>
  <div class="col-lg-3" role="complimentary">
    <nav>
      <ul id="recipe-nav" class="nav">
      </ul>
    </nav>
  </div>
</div>
