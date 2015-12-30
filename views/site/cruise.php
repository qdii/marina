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
use \yii\bootstrap\Html;
use \yii\web\View;

$this->title = 'Cruises';

\app\assets\CruiseAsset::register($this);

$deleteBtn = Html::button(
    '<span class="glyphicon glyphicon-trash"></span>',
    [ 'class' => 'btn btn-danger' ]
);

?>

<div class="page-header">
<h1><?php echo $this->title ?></h1>
</div>

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

<?php
$this->registerJs(
    "var get_cruises_url = '" . Url::toRoute("ajax/get-cruises") . "';\n" .
    "var btn_txt = '$deleteBtn';\n",
    View::POS_BEGIN
);
?>
