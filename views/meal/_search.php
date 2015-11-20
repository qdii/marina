<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\MealSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="meal-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'nbGuests') ?>

    <?= $form->field($model, 'cook') ?>

    <?= $form->field($model, 'date') ?>

    <?= $form->field($model, 'cruise') ?>

    <?php // echo $form->field($model, 'backgroundColor') ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('app', 'Reset'), ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
