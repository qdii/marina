<?php
use \yii\bootstrap\ActiveForm;
use \yii\bootstrap\Button;
use \yii\helpers\Url;
use \yii\helpers\ArrayHelper;
use \app\models\Cruise;
use \skeeks\widget\chosen\Chosen;

$dupForm = [
  'method' => 'post',
  'action' => Url::toRoute(['ajax/duplicate-cruise']),
];

$chosenOpts = [
    'model'     => new \app\models\Cruise,
    'attribute' => 'id',
    'items'     => ArrayHelper::map($cruises, 'id', 'name'),
];

$this->title = "Duplicate cruise";
?>

<div class="page-header">
  <h1><?php echo $this->title ?></h1>
</div>

<?php $form = ActiveForm::begin($dupForm);
  $mdl = new Cruise;
  echo $form->field($mdl, 'id')->widget(Chosen::className(), $chosenOpts);
  echo Button::widget(['label' => 'Duplicate', 'options' => ['class' => 'btn-success', 'tyoe' => 'button'], 'id' => 'btn-duplicate']);
ActiveForm::end(); ?>

