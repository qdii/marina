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
use \yii\widgets\ActiveForm;
use \kartik\widgets\TouchSpin;

$boatSelector = [
  'id'          => 'boat-chosen',
  'model'       => new \app\models\Boat,
  'attribute'   => 'name',
  'placeholder' => 'Choose a boat',
  'items'       => ArrayHelper::map($boats, 'id', 'name')
];

$vendorSelector = [
  'id'          => 'vendor-chosen',
  'model'       => new \app\models\Vendor,
  'attribute'   => 'name',
  'placeholder' => 'Choose a shop',
  'items'       => ArrayHelper::map($vendors, 'id', 'name')
];


$touchSpin = [
  'name'  => 'nb-guests',
  'options' => [
      'placeholder' => 'Nb of guests'
  ],
  'class' => 'form-control',
];

$this->title = 'Cookbook';
?>

<div class="page-header">
  <h1>Cookbook</h1>
</div>

<div class="panel panel-info">
  <div class="panel-heading">
    Settings
  </div>
  <div class="panel-body">
    <div class="row">
      <div class='col-lg-3'><?php echo Chosen::widget($boatSelector) ?></div>
      <div class='col-lg-3'><?php echo Chosen::widget($vendorSelector) ?></div>
      <div class='col-lg-2'><?php echo Touchspin::widget($touchSpin) ?></div>
      </div>
    </div>
  </div>
</div>


